import test from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { validateFlow, currentStep, answerQuestion, rewind, explainPath } from '../wordpress-theme/nicole-ried/assets/orientierung/engine.mjs';

const flow = JSON.parse(await readFile(new URL('../wordpress-theme/nicole-ried/assets/orientierung/flow.json', import.meta.url), 'utf8'));
const walk = answers => answers.reduce((history, answer) => answerQuestion(flow, history, answer), []);

test('configuration has no broken links, cycles, or unreachable outcomes', () => validateFlow(flow));
for (const [result, path] of [
  ['perspektive', ['ja', 'festgefahren', 'ja']],
  ['entscheidung', ['ja', 'entscheidung', 'ja', 'ja']],
  ['grenzen', ['ja', 'beziehung', 'ja', 'ja']],
  ['ressourcen', ['ja', 'belastung', 'ja', 'ja']],
  ['mediation', ['ja', 'beziehung', 'nein', 'ja', 'ja', 'ja']]
]) {
  test(`a complete, suitable path reaches ${result}`, () => assert.equal(currentStep(flow, walk(path)).id, result));
}
for (const answer of ['nein', 'unsicher']) {
  test(`safety answer ${answer} never produces a coaching or mediation recommendation`, () => {
    const step = currentStep(flow, walk([answer]));
    assert.equal(step.data.kind, 'support');
  });
}
test('mediation requires willingness and the ability to speak without pressure', () => {
  assert.notEqual(currentStep(flow, walk(['ja', 'beziehung', 'nein', 'ja', 'nein'])).id, 'mediation');
  assert.equal(currentStep(flow, walk(['ja', 'beziehung', 'nein', 'ja', 'ja', 'unsicher'])).id, 'klaerung');
  assert.equal(currentStep(flow, walk(['ja', 'beziehung', 'nein', 'ja', 'ja', 'nein'])).id, 'klaerung');
});
test('changing the topic discards the old downstream route and explanation', () => {
  const old = walk(['ja', 'entscheidung', 'ja', 'ja']);
  let updated = rewind(old, 1);
  for (const answer of ['belastung', 'ja', 'ja']) updated = answerQuestion(flow, updated, answer);
  assert.equal(currentStep(flow, updated).id, 'ressourcen');
  assert.equal(currentStep(flow, old).id, 'entscheidung');
  assert.ok(explainPath(flow, updated).every(entry => !entry.question.includes('Entscheidung')));
});
test('unknown options and old answer histories are rejected', () => {
  assert.throws(() => answerQuestion(flow, [], 'maybe'));
  assert.throws(() => currentStep(flow, [{ nodeId: 'werte', answerId: 'ja' }]));
  assert.throws(() => answerQuestion(flow, walk(['nein']), 'ja'));
});
test('configuration validator rejects loops and broken results', () => {
  const loop = structuredClone(flow);
  loop.nodes.sicherheit.options[0].next = 'sicherheit';
  assert.throws(() => validateFlow(loop), /Zyklus/);
  const broken = structuredClone(flow);
  broken.nodes.sicherheit.options[0].next = 'result:missing';
  assert.throws(() => validateFlow(broken), /Unbekanntes Ergebnis/);
});
test('all possible paths end; method results always pass the safety gate', () => {
  const outcomes = new Set();
  function explore(history) {
    assert.ok(history.length <= 10, 'a path must not grow indefinitely');
    const step = currentStep(flow, history);
    if (step.type === 'result') {
      outcomes.add(step.id);
      if (step.data.kind === 'method') assert.equal(history[0].answerId, 'ja');
      return;
    }
    for (const option of step.data.options) explore(answerQuestion(flow, history, option.id));
  }
  explore([]);
  assert.equal(outcomes.size, 7);
});
