import { copyFileSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createHash } from 'node:crypto';

const root = new URL('../../', import.meta.url);
const sdk = new URL('node_modules/@friendlycaptcha/sdk/', root);
const { version } = JSON.parse(readFileSync(new URL('package.json', sdk), 'utf8'));
const filename = `sdk@${version}-site.compat.min.js`;
const configuration = readFileSync(new URL('Classes/Configuration.php', root), 'utf8');
if (!configuration.includes(`/JavaScript/lib/${filename}'`)) {
    throw new Error(`Configuration::DEFAULT_JS_PATH must use ${filename} before regenerating the SDK.`);
}
const output = new URL('Resources/Public/JavaScript/lib/', root);
mkdirSync(output, { recursive: true });
copyFileSync(new URL('site.compat.min.js', sdk), new URL(filename, output));
copyFileSync(new URL('LICENSE', sdk), new URL('sdk-LICENSE.txt', output));
const bytes = readFileSync(new URL(filename, output));
writeFileSync(new URL('sdk-source.json', output), JSON.stringify({
    package: '@friendlycaptcha/sdk', version, file: filename, license: 'MPL-2.0',
    source: `https://registry.npmjs.org/@friendlycaptcha/sdk/-/sdk-${version}.tgz`,
    sha256: createHash('sha256').update(bytes).digest('hex'),
}, null, 2) + '\n');
console.log(`Copied ${filename} (${bytes.length} bytes), upstream MPL license and source provenance.`);
