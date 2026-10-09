// Generates 15 Pinterest pin drafts as 1000x1500 SVGs in Brush With Me brand style.
// Uses the live-site palette (mu-plugin CSS): ink #17364c, sky #eaf6fb, mint #dff4eb, coral #f4836d, paper #fffdf9.
const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '..', 'content', 'pins', 'svg');
fs.mkdirSync(OUT, { recursive: true });

const SITE = 'https://brushwithme.com/';

const pins = [
  { n: '01', slug: 'brush-or-floss-first', hook: ["You've been", 'brushing in the', 'wrong order', '(probably)'], sub: 'Floss first? A hygienist explains.' },
  { n: '02', slug: '2-2-2-rule-brushing-teeth', hook: ['The 2-2-2 rule', 'dentists wish', 'everyone followed'], sub: 'Brush 2x a day · 2 minutes · dentist 2x a year' },
  { n: '03', slug: 'kids-brushing-routine-by-age', hook: ['What age can kids', 'REALLY brush', 'alone?'], sub: "Later than you think — the age-by-age guide" },
  { n: '04', slug: 'how-much-toothpaste-by-age', hook: ['Rice grain, pea', 'or ribbon?', 'Toothpaste by age'], sub: 'The amounts that protect little teeth' },
  { n: '05', slug: 'electric-vs-manual-toothbrush-kids', hook: ['Is the $40', 'electric brush', 'worth it for kids?'], sub: "A hygienist's honest answer" },
  { n: '06', slug: 'brushing-too-hard', hook: ['Brushing harder is', 'quietly damaging', 'your gums'], sub: '3 signs + the gentle fix' },
  { n: '07', slug: 'water-flossers-for-kids', hook: ['The flossing fight', 'ends tonight'], sub: 'Water flossers for kids: the honest guide' },
  { n: '08', slug: 'when-can-kids-floss-alone', hook: ['Can your kid tie', 'their shoes?', 'Then they can floss alone'], sub: 'The 2-minute kitchen test' },
  { n: '09', slug: '', hook: ['Dental Health', 'Month is', 'coming!'], sub: 'No-prep classroom packs K-2 + 3-5 (February)' },
  { n: '10', slug: '', hook: ['Make brushing', 'a habit in', '21 days'], sub: 'FREE printable challenge' },
  { n: '11', slug: '', hook: ['End the brushing', 'battles —', 'tonight'], sub: 'Charts, reward tickets & scripts · $24 kit' },
  { n: '12', slug: '2-2-2-rule-brushing-teeth', hook: ['Why morning', 'breath happens', '(+ the fix)'], sub: "It's the overnight buffet — here's the routine" },
  { n: '13', slug: 'brushing-too-hard', hook: ['The 45\u00B0 brush', 'trick that cleans', 'the gumline'], sub: 'Gentle circles at the right angle' },
  { n: '14', slug: 'sugar-and-kids-teeth', hook: ["It's not HOW", 'MUCH sugar —', "it's HOW OFTEN"], sub: 'The 20-minute acid rule, explained' },
  { n: '15', slug: 'about', hook: ['Every guide here', 'is reviewed by', 'a real hygienist'], sub: 'Meet Jessie, RDH — 25 years in clinical care' },
];

const esc = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

function svgFor(p) {
  const hookStartY = 470, lineH = 104;
  const lines = p.hook.map((line, i) => `<tspan x="80" dy="${i === 0 ? 0 : lineH}">${esc(line)}</tspan>`).join('');
  const underlineY = hookStartY + p.hook.length * lineH + 6;
  const subY = underlineY + 92;
  const href = SITE + (p.slug ? p.slug.replace(/^\/+|\/+$/g, '') + '/' : '');
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1000" height="1500" viewBox="0 0 1000 1500">
  <a href="${href}">
    <rect width="1000" height="1500" fill="#fffdf9"/>
    <circle cx="880" cy="170" r="255" fill="#dff4eb"/>
    <circle cx="110" cy="1370" r="185" fill="#eaf6fb"/>
    <circle cx="905" cy="1215" r="26" fill="#f4836d"/>
    <text x="78" y="125" font-family="Trebuchet MS, Verdana, DejaVu Sans, sans-serif" font-size="32" font-weight="bold" letter-spacing="7" fill="#4a8294">BRUSH WITH ME</text>
    <text x="80" y="${hookStartY}" font-family="Trebuchet MS, Verdana, DejaVu Sans, sans-serif" font-size="84" font-weight="bold" fill="#17364c">${lines}</text>
    <rect x="82" y="${underlineY}" width="230" height="14" rx="7" fill="#f4836d"/>
    <text x="80" y="${subY}" font-family="Trebuchet MS, Verdana, DejaVu Sans, sans-serif" font-size="40" fill="#527085">${esc(p.sub)}</text>
    <text x="80" y="1332" font-family="Trebuchet MS, Verdana, DejaVu Sans, sans-serif" font-size="36" font-weight="bold" fill="#17364c">\u2713 Reviewed by Jessie, RDH \u00B7 25 years clinical</text>
    <text x="80" y="1388" font-family="Trebuchet MS, Verdana, DejaVu Sans, sans-serif" font-size="28" fill="#4a8294">brushwithme.com</text>
  </a>
  <!-- Pin ${p.n} \u00B7 target: ${href} -->
</svg>`;
}

let written = 0;
for (const p of pins) {
  const base = 'pin-' + p.n + '-' + (p.slug ? p.slug : 'site-root');
  fs.writeFileSync(path.join(OUT, base + '.svg'), svgFor(p), 'utf8');
  written++;
}
console.log('Generated ' + written + ' pins in ' + OUT);