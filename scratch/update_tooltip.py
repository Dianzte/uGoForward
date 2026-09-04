import re

with open('resources/js/homepage.js', 'r', encoding='utf-8') as f:
    content = f.read()

replacement = '''        tooltipUnisText.textContent = n === 1 
          ? '1 ' + (window.mapTranslations?.university || 'universidad') + ' ' + (window.mapTranslations?.withActiveScholarships || 'con becas activas')
          : n + ' ' + (window.mapTranslations?.unisSuffix || 'universidades') + ' ' + (window.mapTranslations?.withActiveScholarships || 'con becas activas');'''

content = re.sub(
    r"tooltipUnisText\.textContent = n === 1 \? '1 university with scholarships' : `\$\{n\} universities with.*?scholarships`;",
    replacement,
    content,
    flags=re.DOTALL
)

with open('resources/js/homepage.js', 'w', encoding='utf-8') as f:
    f.write(content)
