// Shared by the browser and the routing tests.
export function validateFlow(flow) {
  if (!flow?.nodes?.[flow.start] || !flow.results) throw new Error('Start oder Ergebnisse fehlen.');
  const visited = new Set();
  const active = new Set();
  const results = new Set();
  function visit(id) {
    if (id.startsWith('result:')) {
      const resultId = id.slice(7);
      if (!flow.results[resultId]) throw new Error(`Unbekanntes Ergebnis: ${resultId}`);
      results.add(resultId);
      return;
    }
    if (active.has(id)) throw new Error(`Zyklus im Fragenweg: ${id}`);
    if (visited.has(id)) return;
    const node = flow.nodes[id];
    if (!node || !node.title || !Array.isArray(node.options) || node.options.length === 0) {
      throw new Error(`Ungültige Frage: ${id}`);
    }
    if (new Set(node.options.map(option => option.id)).size !== node.options.length) {
      throw new Error(`Doppelte Antwort: ${id}`);
    }
    active.add(id);
    for (const option of node.options) {
      if (!option.id || !option.label || typeof option.next !== 'string') throw new Error(`Ungültige Antwort: ${id}`);
      visit(option.next);
    }
    active.delete(id);
    visited.add(id);
  }
  visit(flow.start);
  if (visited.size !== Object.keys(flow.nodes).length) throw new Error('Nicht erreichbare Fragen.');
  if (results.size !== Object.keys(flow.results).length) throw new Error('Nicht erreichbare Ergebnisse.');
  return flow;
}

export function currentStep(flow, history = []) {
  let target = flow.start;
  for (const entry of history) {
    if (target.startsWith('result:') || entry.nodeId !== target) throw new Error('Veralteter Antwortpfad.');
    const option = flow.nodes[target]?.options.find(item => item.id === entry.answerId);
    if (!option) throw new Error('Unbekannte Antwort.');
    target = option.next;
  }
  return target.startsWith('result:')
    ? { type: 'result', id: target.slice(7), data: flow.results[target.slice(7)] }
    : { type: 'question', id: target, data: flow.nodes[target] };
}

export function answerQuestion(flow, history, answerId) {
  const step = currentStep(flow, history);
  if (step.type !== 'question' || !step.data.options.some(option => option.id === answerId)) {
    throw new Error('Diese Antwort ist hier nicht möglich.');
  }
  return [...history, { nodeId: step.id, answerId }];
}

export function rewind(history, index) {
  if (!Number.isInteger(index) || index < 0 || index > history.length) throw new Error('Ungültiger Rücksprung.');
  return history.slice(0, index);
}

export function explainPath(flow, history) {
  currentStep(flow, history);
  return history.map(entry => {
    const node = flow.nodes[entry.nodeId];
    const option = node.options.find(item => item.id === entry.answerId);
    return { nodeId: entry.nodeId, label: node.label, question: node.title, answer: option.label, evidence: option.evidence };
  });
}
