import json

# Read the English file
with open(r'c:\laragon\www\ugf\lang\en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Read the Spanish file
with open(r'c:\laragon\www\ugf\lang\es.json', 'r', encoding='utf-8') as f:
    es = json.load(f)

# Add missing translations for the map modal
translations = {
    "Construction Information": "Información en construcción",
    "Incoming Universities": "Próximamente agregaremos las universidades de",
    "We are working on it": "¡Estamos trabajando en ello!",
    "Universities Loaded": "universidades cargadas",
    "with active scholarships": "con becas activas",
    "university": "universidad",
    "universities": "universidades",
    "with scholarships": "con becas",
    "El Salvador": "El Salvador",
    "Department": "Departamento",
    "No data available": "No hay información disponible",
    "Close": "Cerrar"
}

# Add translations to both files
for en_key, es_value in translations.items():
    en[en_key] = en_key
    es[en_key] = es_value

# Add department names
departments = [
    "Ahuachapán", "Santa Ana", "Sonsonate", "Chalatenango", 
    "La Libertad", "San Salvador", "Cuscatlán", "La Paz", 
    "Cabañas", "San Vicente", "Usulután", "San Miguel", 
    "Morazán", "La Unión"
]

for dept in departments:
    if dept not in en:
        en[dept] = dept
    if dept not in es:
        es[dept] = dept

# Write updated files
with open(r'c:\laragon\www\ugf\lang\en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, ensure_ascii=False, indent=2)

with open(r'c:\laragon\www\ugf\lang\es.json', 'w', encoding='utf-8') as f:
    json.dump(es, f, ensure_ascii=False, indent=2)

print("✓ Translation files updated")
print(f"✓ Added {len(translations)} new translation keys")
print(f"✓ Total keys in en.json: {len(en)}")
print(f"✓ Total keys in es.json: {len(es)}")
