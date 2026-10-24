import test from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import vm from 'node:vm';

const source = await readFile(new URL('../wordpress-theme/nicole-ried/assets/js/site.js', import.meta.url), 'utf8');
const classList = () => {
  const values = new Set();
  return {
    add: (...names) => names.forEach(name => values.add(name)),
    remove: (...names) => names.forEach(name => values.delete(name)),
    contains: name => values.has(name),
    toggle: (name, on) => on ? values.add(name) : values.delete(name)
  };
};
const element = () => ({ classList: classList(), style: { setProperty() {} } });

function mount({ ready = true, reduced = false, rejectPlay = false, heading = true } = {}) {
  let now = 0;
  let frames = [];
  const letters = [];
  const title = element();
  const hero = { ...element(), querySelector: () => heading ? title : null };
  const listeners = new Map();
  const video = {
    ...element(), readyState: ready ? 1 : 0, duration: 12, currentTime: 0,
    paused: true, ended: false, plays: 0,
    addEventListener(name, callback) { listeners.set(name, callback); },
    play() {
      this.plays++;
      this.paused = rejectPlay;
      return rejectPlay ? Promise.reject(new Error('Autoplay blocked')) : Promise.resolve();
    }
  };
  const path = { ...element(), getTotalLength: () => 100, getPointAtLength: x => ({ x, y: x }) };
  const claims = [element(), element(), element()];
  const group = { setAttribute() {} };
  const svg = { setAttribute() {}, querySelector: () => group };
  const media = {
    ...element(), getBoundingClientRect: () => ({ width: 640, height: 600 }),
    querySelector: selector => ({ '.hero-video': video, '.hero-path path': path, '.hero-path': svg }[selector] || element()),
    querySelectorAll: () => claims
  };
  const nodes = ['Keinen Bock', 'auf', ' Stress?'].map(textContent => ({
    textContent, replaceWith: fragment => letters.push(...fragment.children)
  }));
  const document = {
    querySelector: selector => selector === '.hero' ? hero : selector === '.hero-media' ? media : null,
    querySelectorAll: () => [],
    documentElement: { clientWidth: 1366, style: { setProperty() {} } },
    createTreeWalker() {
      let index = 0;
      return { currentNode: null, nextNode() { this.currentNode = nodes[index++]; return this.currentNode; } };
    },
    createElement: element,
    createDocumentFragment: () => ({ children: [], append(child) { this.children.push(child); } })
  };
  vm.runInNewContext(source, {
    document, NodeFilter: { SHOW_TEXT: 4 },
    matchMedia: () => ({ matches: reduced }),
    performance: { now: () => now },
    requestAnimationFrame: callback => frames.push(callback),
    addEventListener() {},
    IntersectionObserver: class { observe() {} }
  });
  return {
    video, title, hero, letters, claims,
    metadata() { video.readyState = 1; listeners.get('loadedmetadata')?.(); },
    async frame(time) {
      now = time;
      const pending = frames;
      frames = [];
      pending.forEach(callback => callback(time));
      await Promise.resolve();
      await Promise.resolve();
    }
  };
}

test('ready video stays paused while the heading is typed, then starts once', async () => {
  const page = mount();
  assert.equal(page.letters.map(letter => letter.textContent).join(''), 'Keinen Bockauf Stress?');
  await page.frame(700);
  const visible = page.letters.filter(letter => letter.classList.contains('is-visible')).length;
  assert.ok(visible > 0 && visible < page.letters.length);
  assert.equal(page.video.plays, 0);
  assert.equal(page.video.paused, true);
  assert.equal(page.hero.classList.contains('is-intro-complete'), false);
  await page.frame(3000);
  assert.equal(page.video.plays, 1);
  assert.equal(page.hero.classList.contains('is-intro-complete'), true);
  assert.equal(page.title.classList.contains('is-typing'), false);
  await page.frame(4000);
  assert.equal(page.video.plays, 1);
});

test('late video metadata does not restart or block the completed heading', async () => {
  const page = mount({ ready: false });
  await page.frame(3000);
  assert.equal(page.hero.classList.contains('is-intro-complete'), true);
  assert.equal(page.video.plays, 0);
  page.metadata();
  assert.equal(page.video.plays, 1);
  assert.equal(page.video.currentTime, 4.4);
  page.metadata();
  assert.equal(page.video.plays, 1);
});

test('reduced motion skips typing and playback and shows the final state', async () => {
  const page = mount({ reduced: true });
  await page.frame(0);
  assert.equal(page.letters.length, 0);
  assert.equal(page.video.plays, 0);
  assert.equal(page.video.currentTime, 11.9);
  assert.ok(page.claims.every(claim => claim.classList.contains('on')));
});

test('blocked autoplay still reveals the claims and completes the intro', async () => {
  const page = mount({ rejectPlay: true });
  await page.frame(3000);
  await page.frame(3100);
  assert.equal(page.hero.classList.contains('is-intro-complete'), true);
  assert.ok(page.claims.every(claim => claim.classList.contains('on')));
});

test('missing optional heading does not prevent the video from starting', async () => {
  const page = mount({ heading: false });
  await page.frame(0);
  assert.equal(page.video.plays, 1);
});
