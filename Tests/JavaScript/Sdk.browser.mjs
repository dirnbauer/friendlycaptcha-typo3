import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import { chromium } from '@playwright/test';

// Exercise the shipped SDK, including its real cross-frame message handling.
// Only the remote challenge frames are fixtures: no CAPTCHA or API is contacted.
test('shipped SDK auto-attach, response, reset, destroy and remount lifecycle', async () => {
  const packageJson = JSON.parse(readFileSync(new URL('../../package.json', import.meta.url)));
  const version = packageJson.devDependencies['@friendlycaptcha/sdk'];
  const sdk = readFileSync(new URL(`../../Resources/Public/JavaScript/lib/sdk@${version}-site.compat.min.js`, import.meta.url));
  const browser = await chromium.launch({ headless: true });
  try {
    const page = await browser.newPage();
    const errors = [];
    const requests = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.route('**/*', async route => {
      const url = new URL(route.request().url());
      requests.push(url.origin + url.pathname);
      if (url.origin === 'https://form.test' && url.pathname === '/sdk.js') {
        return route.fulfill({ contentType: 'text/javascript', body: sdk });
      }
      if (url.origin === 'https://form.test' && url.pathname === '/') {
        return route.fulfill({ contentType: 'text/html', body: `<!doctype html><html lang="de"><form><input name="name"><div class="frc-captcha" data-sitekey="fixture-key" data-start="none" data-api-endpoint="https://captcha.test"></div></form><script src="/sdk.js" defer></script></html>` });
      }
      if (url.origin === 'https://captcha.test' && /^\/api\/v2\/captcha\/(agent|widget)$/.test(url.pathname)) {
        const type = url.pathname.endsWith('/agent') ? 'agent' : 'widget';
        return route.fulfill({ contentType: 'text/html', body: `<script>const id=new URLSearchParams(location.search).get('comm_id');parent.postMessage({_frc:1,from_id:id,to_id:'',type:'${type}_announce'},'https://form.test');</script>` });
      }
      throw new Error(`Unexpected network request: ${url.origin}${url.pathname}`);
    });
    await page.goto('https://form.test/');
    await page.waitForFunction(() => window.frcaptcha && document.querySelector('.frc-captcha').frcWidget);
    assert.deepEqual(await page.evaluate(async () => {
      const [widget] = await window.frcaptcha.attached;
      window.fixtureWidget = widget;
      window.fixtureEvents = [];
      widget.addEventListener('frc:widget.complete', event => window.fixtureEvents.push(event.detail));
      widget.addEventListener('frc:widget.reset', event => window.fixtureEvents.push(event.detail));
      return { count: (await window.frcaptcha.attached).length, duplicate: window.frcaptcha.attach().length, inputs: document.querySelectorAll('[name="frc-captcha-response"]').length };
    }), { count: 1, duplicate: 0, inputs: 1 });
    const widgetFrame = page.frames().find(frame => frame.url().includes('/captcha/widget'));
    assert.ok(widgetFrame);
    assert.equal(new URL(widgetFrame.url()).searchParams.get('lang'), 'de');
    assert.equal(new URL(widgetFrame.url()).searchParams.get('sitekey'), 'fixture-key');
    await widgetFrame.evaluate(() => {
      const id = new URLSearchParams(location.search).get('comm_id');
      parent.postMessage({ _frc: 1, from_id: id, to_id: '', type: 'root_set_response', widget_id: id, state: 'completed', response: 'fixture-response' }, 'https://form.test');
    });
    await page.waitForFunction(() => window.fixtureWidget.getState() === 'completed');
    assert.deepEqual(await page.evaluate(() => ({ response: window.fixtureWidget.getResponse(), field: new FormData(document.querySelector('form')).get('frc-captcha-response'), event: window.fixtureEvents.at(-1).name })), { response: 'fixture-response', field: 'fixture-response', event: 'frc:widget.complete' });
    assert.deepEqual(await page.evaluate(() => {
      window.fixtureWidget.reset();
      return { response: window.fixtureWidget.getResponse(), field: new FormData(document.querySelector('form')).get('frc-captcha-response'), event: window.fixtureEvents.at(-1).name };
    }), { response: '.RESET', field: '.RESET', event: 'frc:widget.reset' });
    assert.deepEqual(await page.evaluate(() => {
      const element = window.fixtureWidget.getElement();
      window.fixtureWidget.destroy();
      const inputsAfterDestroy = document.querySelectorAll('[name="frc-captcha-response"]').length;
      const remounted = window.frcaptcha.createWidget({ element, sitekey: 'fixture-key', startMode: 'none', apiEndpoint: 'https://captcha.test' });
      return { destroyed: window.fixtureWidget.isDestroyed, inputsAfterDestroy, remounted: remounted.id !== window.fixtureWidget.id, inputs: document.querySelectorAll('[name="frc-captcha-response"]').length };
    }), { destroyed: true, inputsAfterDestroy: 0, remounted: true, inputs: 1 });
    assert.deepEqual(errors, []);
    assert.ok(requests.every(url => url.startsWith('https://form.test/') || url.startsWith('https://captcha.test/')));
  } finally {
    await browser.close();
  }
});
