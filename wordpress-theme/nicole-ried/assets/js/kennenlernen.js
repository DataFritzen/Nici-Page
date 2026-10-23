'use strict';

const topics = {
  grenzen: { category: 'Resilienz · Grenzen', title: 'Wie sage ich Nein, ohne mich mies zu fühlen?', lead: 'Dein Nein erzählt auch etwas darüber, was dir wichtig ist.', body: 'Wir schauen, wann dir ein Nein schwerfällt, was du in diesen Momenten befürchtest und wo deine Grenze liegt. Dann suchen wir Worte, die zu dir passen, und kleine Gelegenheiten, sie im Alltag auszuprobieren.', target: 'resilienz-weg' },
  konflikte: { category: 'Mediation · Miteinander', title: 'Warum streiten wir immer über dasselbe?', lead: 'Vielleicht geht es längst um mehr als den Auslöser.', body: 'Wir schauen gemeinsam auf die wiederkehrenden Situationen: Was wird gesagt, was kommt an und was bleibt unausgesprochen? In der Mediation bekommen die unterschiedlichen Sichtweisen Raum, damit wieder ein Gespräch und eigene Vereinbarungen möglich werden.', target: 'mediation-weg' },
  gedankenkreisen: { category: 'Resilienz · Gedankenkreisen', title: 'Warum lässt mich das nicht los?', lead: 'Ein Gedanke braucht manchmal erst Aufmerksamkeit, bevor er leiser werden kann.', body: 'Wir schauen, was dich an der Situation noch beschäftigt und was du gerade brauchst. Dabei unterscheiden wir, worauf du Einfluss hast und was außerhalb deiner Hand liegt. Daraus suchen wir einen Umgang, der dich im Alltag entlasten kann.', target: 'resilienz-weg' },
  richtung: { category: 'Coaching · Eigener Weg', title: 'Was will ich eigentlich selbst?', lead: 'Zwischen all den Erwartungen darf deine eigene Stimme wieder Platz bekommen.', body: 'Wir sortieren, was andere von dir erwarten und was dir selbst wichtig ist. Du kannst Möglichkeiten durchdenken und prüfen, was sich für dein Leben stimmig anfühlt. Eine Entscheidung muss dabei nicht schon am Anfang feststehen.', target: 'coaching-weg' },
  anfangen: { category: 'Coaching · Losgehen', title: 'Ich weiß es doch. Warum fange ich nicht an?', lead: 'Vielleicht braucht der erste Schritt eine andere Größe.', body: 'Wir schauen, was zwischen deinem Vorhaben und dem Anfangen steht. Dann machen wir den nächsten Schritt konkret und prüfen, wie er in deinen tatsächlichen Alltag passt. Auch mögliche Hindernisse dürfen dabei von Anfang an mit auf den Tisch.', target: 'coaching-weg' },
  veraenderung: { category: 'Resilienz · Veränderung', title: 'Und wenn alles anders kommt?', lead: 'Du brauchst noch keinen Plan für alles, was kommt.', body: 'Wir schauen zuerst, was dir jetzt Halt gibt: Menschen, Erfahrungen und Dinge, die dir guttun. Von dort aus sortieren wir, was gerade ansteht und worauf du Einfluss nehmen kannst. So kann ein nächster Schritt entstehen, auch wenn vieles offenbleibt.', target: 'resilienz-weg' }
};

const dialogOpeners = new WeakMap();
function openDialog(dialog, opener) {
  dialogOpeners.set(dialog, opener);
  dialog.showModal();
  dialog.scrollTop = 0;
}
document.querySelectorAll('[data-dialog]').forEach(button => {
  button.addEventListener('click', () => openDialog(document.getElementById(button.dataset.dialog), button));
});
document.querySelectorAll('dialog').forEach(dialog => {
  dialog.querySelector('.dialog-close').addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', event => {
    const rect = dialog.getBoundingClientRect();
    if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
  });
  dialog.addEventListener('close', () => {
    if (!document.querySelector('dialog[open]')) dialogOpeners.get(dialog)?.focus({ preventScroll: true });
  });
});

const answerDialog = document.getElementById('thought-dialog');
const answerPath = document.getElementById('answer-path');
document.querySelectorAll('[data-topic]').forEach(button => {
  button.addEventListener('click', () => {
    const topic = topics[button.dataset.topic];
    document.getElementById('answer-category').textContent = topic.category;
    document.getElementById('answer-title').textContent = topic.title;
    document.getElementById('answer-lead').textContent = topic.lead;
    document.getElementById('answer-body').textContent = topic.body;
    answerPath.href = '#' + topic.target;
    openDialog(answerDialog, button);
  });
});
answerPath.addEventListener('click', () => answerDialog.close());

const wave = document.querySelector('.wave');
for (let index = 0; index < 120; index++) {
  const bar = document.createElement('i');
  const envelope = Math.sin(index / 119 * Math.PI);
  const variation = Math.abs(Math.sin(index * 1.7) * Math.cos(index * .31));
  bar.style.height = (4 + envelope * variation * 65) + 'px';
  wave.append(bar);
}

// Connect actual element centers so the map follows both desktop and mobile layouts.
const map = document.querySelector('.thought-map');
const canvas = map.querySelector('canvas');
const mapButtons = [...map.querySelectorAll('.thought')];
function drawConnections() {
  const bounds = map.getBoundingClientRect();
  const ratio = window.devicePixelRatio || 1;
  canvas.width = Math.round(bounds.width * ratio);
  canvas.height = Math.round(bounds.height * ratio);
  const context = canvas.getContext('2d');
  context.scale(ratio, ratio);
  context.strokeStyle = '#b6bba9';
  context.lineWidth = 1;
  const points = mapButtons.map(button => {
    const rect = button.getBoundingClientRect();
    return { x: rect.left + rect.width / 2 - bounds.left, y: rect.top + rect.height / 2 - bounds.top };
  });
  points.forEach((point, index) => {
    const other = points[(index + 1) % points.length];
    context.beginPath();
    context.moveTo(point.x, point.y);
    context.bezierCurveTo(bounds.width * .46, point.y, bounds.width * .54, other.y, other.x, other.y);
    context.stroke();
  });
}
new ResizeObserver(drawConnections).observe(map);
document.fonts.ready.then(drawConnections);
