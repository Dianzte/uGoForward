const fs = require('fs');
const txt = fs.readFileSync('composer.lock', 'utf8');
let objDepth = 0;
let arrDepth = 0;
let inString = false;
let escape = false;
let line = 1;
for (let i = 0; i < txt.length; i++) {
    const c = txt[i];
    if (c === '\n') line++;
    if (inString) {
        if (escape) escape = false;
        else if (c === '\\') escape = true;
        else if (c === '"') inString = false;
    } else {
        if (c === '"') inString = true;
        else if (c === '{') objDepth++;
        else if (c === '}') {
            objDepth--;
            if (objDepth < 1) { console.log('objDepth dropped to ' + objDepth + ' at line ' + line); break; }
        }
        else if (c === '[') arrDepth++;
        else if (c === ']') {
            arrDepth--;
            if (arrDepth === 0) { console.log('arrDepth dropped to ' + arrDepth + ' at line ' + line); }
        }
    }
}
