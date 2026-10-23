import { validateFlow, currentStep, answerQuestion, rewind, explainPath } from './engine.mjs';

const $ = selector => document.querySelector(selector);
const stage = $('#stage');
const contactHref = $('#result-action').getAttribute('href');
const title = $('#stage-title');
const description = $('#stage-description');
const intro = $('#intro-form');
const questionPanel = $('#question-panel');
const resultPanel = $('#result-panel');
const concernInput = $('#concern');
const restartDialog = $('#restart-dialog');
const aboutDialog = $('#about-dialog');
const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
let flow;
let history = [];
let concern = '';
let mode = 'intro';
let lastOpener;

function element(tag, className, text) {
  const node = document.createElement(tag);
  if (className) node.className = className;
  if (text !== undefined) node.textContent = text;
  return node;
}

function updateCounter() {
  $('#character-count').textContent = `${concernInput.value.length} / 700`;
}

function editConcern() {
  mode = 'intro';
  concernInput.value = concern;
  updateCounter();
  render();
  concernInput.focus({ preventScroll: true });
}

function goBack() {
  if (history.length === 0) {
    editConcern();
    return;
  }
  history = rewind(history, history.length - 1);
  mode = 'question';
  render();
}

function renderPath(step) {
  const path = $('#path');
  path.replaceChildren();
  $('#concern-preview').hidden = !concern;
  $('#concern-text').textContent = concern.length > 140 ? concern.slice(0, 140) + '…' : concern;
  $('#path-count').textContent = String(mode === 'intro' ? 1 : history.length + 2).padStart(2, '0');
  const startItem = element('li', mode === 'intro' ? 'path-current' : 'path-done');
  if (mode === 'intro') {
    startItem.append(element('span', '', 'Dein Anliegen'), element('small', '', 'Hier fangen wir an.'));
  } else {
    const edit = element('button', '', '');
    edit.type = 'button';
    edit.setAttribute('aria-label', 'Anliegen bearbeiten und neu beginnen');
    edit.append(element('span', '', 'Dein Anliegen'), element('small', '', concern ? 'In deinen Worten' : 'Noch offen'));
    edit.addEventListener('click', editConcern);
    startItem.append(edit);
  }
  path.append(startItem);
  if (mode === 'intro') {
    for (const label of ['Ein paar Fragen', 'Ein möglicher Anfang']) {
      const item = element('li', 'path-pending');
      item.append(element('span', '', label));
      path.append(item);
    }
    return;
  }
  explainPath(flow, history).forEach((entry, index) => {
    const item = element('li', 'path-done');
    const button = element('button');
    button.type = 'button';
    button.title = entry.question;
    button.setAttribute('aria-label', `Antwort ändern: ${entry.label}, ${entry.answer}`);
    button.append(element('span', '', entry.label), element('small', '', entry.answer));
    button.addEventListener('click', () => {
      history = rewind(history, index);
      mode = 'question';
      render();
    });
    item.append(button);
    path.append(item);
  });
  const current = element('li', 'path-current');
  current.append(element('span', '', step.data.label), element('small', '', step.type === 'result' ? 'Ein möglicher nächster Schritt' : 'Hier bist du gerade.'));
  path.append(current);
}

function renderQuestion(step) {
  $('#stage-label').textContent = step.data.label;
  $('#step-count').textContent = `Frage ${history.length + 1}`;
  title.textContent = step.data.title;
  description.textContent = step.data.description;
  const options = $('#options');
  options.classList.toggle('topic-answers', step.data.layout === 'topics');
  options.replaceChildren();
  for (const option of step.data.options) {
    const button = element('button', 'answer-button');
    button.type = 'button';
    button.append(element('span', '', option.label));
    if (step.data.layout === 'topics') {
      const arrow = element('span', '', '↗');
      arrow.setAttribute('aria-hidden', 'true');
      button.append(arrow);
    }
    button.addEventListener('click', () => {
      history = answerQuestion(flow, history, option.id);
      render();
    });
    options.append(button);
  }
  $('.question-note').hidden = step.data.layout === 'topics';
}

function renderResult(step) {
  const result = step.data;
  $('#step-count').textContent = 'Dein nächster Schritt';
  $('#stage-label').textContent = result.label;
  title.textContent = result.title;
  description.textContent = result.description;
  $('#result-kind').textContent = result.kind === 'method' ? `Ansatz ${result.number} / 05 · Vorschlag zur Abstimmung` : result.kind === 'support' ? 'Persönliche Fachberatung' : 'Noch kein eindeutiger Ansatz';
  const reasons = $('#reason-list');
  reasons.replaceChildren();
  const evidence = [...new Set(explainPath(flow, history).map(entry => entry.evidence).filter(Boolean))];
  if (result.kind === 'support') {
    const safety = explainPath(flow, history)[0];
    reasons.append(element('li', '', `Du hast die Frage nach Sicherheit mit „${safety.answer}“ beantwortet.`));
  } else {
    for (const reason of evidence.length ? evidence : ['Der gewählte Fragenweg führt noch zu keinem eindeutigen Arbeitsansatz.']) reasons.append(element('li', '', reason));
  }
  $('#first-step').hidden = !result.question;
  $('#first-step-question').textContent = result.question || '';
  const action = $('#result-action');
  action.replaceChildren(document.createTextNode(result.action));
  const arrow = element('span', '', '↗');
  arrow.setAttribute('aria-hidden', 'true');
  action.append(arrow);
  action.href = result.href || contactHref;
  $('#result-note').textContent = result.kind === 'support'
    ? 'Der Link führt zu einer Übersicht offizieller Beratungsangebote. Deine Angaben werden nicht mitgegeben.'
    : 'Die Arbeitsansätze sind im Prototyp vorläufig. Ob dieser Vorschlag zu dir und zu Nicoles Angebot passt, klärt ihr persönlich. Der Kontaktlink überträgt keine Antworten.';
}

function render(focus = true) {
  const step = mode === 'intro' ? null : currentStep(flow, history);
  intro.hidden = mode !== 'intro';
  questionPanel.hidden = !step || step.type !== 'question';
  resultPanel.hidden = !step || step.type !== 'result';
  stage.className = `stage ${mode === 'intro' ? 'intro' : step.type === 'result' ? 'result-' + step.data.kind : 'question'}`;
  $('#back').hidden = mode === 'intro';
  if (mode === 'intro') {
    $('#stage-label').textContent = 'Ein Satz. Ein erster Schritt.';
    $('#step-count').textContent = 'Dein Anliegen';
    title.replaceChildren(document.createTextNode('Wo fangen'), document.createElement('br'), document.createTextNode('wir '), element('em', '', 'an?'));
    description.textContent = 'Was beschäftigt dich gerade?';
  } else if (step.type === 'question') renderQuestion(step);
  else renderResult(step);
  renderPath(step);
  if (focus) {
    title.focus({ preventScroll: true });
    stage.scrollIntoView({ behavior: reduceMotion ? 'instant' : 'smooth', block: 'start' });
    $('#announcer').textContent = $('#step-count').textContent;
  }
}

function openDialog(dialog, opener) {
  lastOpener = opener;
  dialog.showModal();
}
for (const dialog of [restartDialog, aboutDialog]) {
  dialog.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => dialog.close()));
  dialog.addEventListener('close', () => lastOpener?.focus({ preventScroll: true }));
}
$('#about').addEventListener('click', event => openDialog(aboutDialog, event.currentTarget));
$('#restart').addEventListener('click', event => openDialog(restartDialog, event.currentTarget));
$('#confirm-restart').addEventListener('click', () => {
  restartDialog.close();
  history = [];
  concern = '';
  concernInput.value = '';
  updateCounter();
  mode = 'intro';
  render();
});
$('#back').addEventListener('click', goBack);
$('#review').addEventListener('click', goBack);
$('#edit-concern').addEventListener('click', editConcern);
concernInput.addEventListener('input', updateCounter);
intro.addEventListener('submit', event => {
  event.preventDefault();
  if (!flow) return;
  concern = concernInput.value.trim().slice(0, 700);
  history = [];
  mode = 'question';
  render();
});

async function loadFlow() {
  const status = $('#load-status');
  status.hidden = false;
  status.classList.remove('load-error');
  status.textContent = 'Fragenweg wird geladen …';
  $('#retry').hidden = true;
  try {
    const response = await fetch(new URL('./flow.json', import.meta.url), { credentials: 'omit' });
    if (!response.ok) throw new Error('Fragen nicht erreichbar.');
    flow = validateFlow(await response.json());
    $('#start').disabled = false;
    $('#restart').disabled = false;
    status.hidden = true;
    render(false);
  } catch {
    status.textContent = 'Der Fragenweg konnte nicht geladen werden. Bitte versuche es noch einmal.';
    status.classList.add('load-error');
    $('#retry').hidden = false;
  }
}
$('#retry').addEventListener('click', loadFlow);
loadFlow();
