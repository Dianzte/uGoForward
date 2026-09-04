import re

with open('resources/js/homepage.js', 'r', encoding='utf-8') as f:
    content = f.read()

new_openMapModal = '''function openMapModal(deptId) {
  const path = document.getElementById(deptId);
  if (!path) return;
  
  const name = path.getAttribute("data-name") || deptId;
  const unisStr = path.getAttribute("data-universities");
  let unis = [];
  try {
    if (unisStr) unis = JSON.parse(unisStr);
  } catch(e) { 
    console.error("Error parsing JSON for " + name, e); 
  }

  const n = unis.length;

  if (n === 0) {
    mapModalBody.innerHTML = `<div class="map-modal-empty">
        <div class="empty-icon">🏗️</div>
        <h3>${window.mapTranslations?.constructionInfo || "Información en construcción"}</h3>
        <p>${window.mapTranslations?.workingOnIt || "Estamos trabajando en ello"}</p>
      </div>`;
    modalDeptNameEl.textContent = name;
    modalDeptUniCount.textContent = "0 " + (window.mapTranslations?.unisSuffix || "universidades");
    modalDeptRegion.textContent = window.mapTranslations?.elSalvador || "El Salvador";
    mapModalOverlay.classList.add('open');
    return;
  }

  modalDeptNameEl.textContent = name;
  modalDeptUniCount.textContent = n + " " + (window.mapTranslations?.unisSuffix || "universidades");
  modalDeptRegion.textContent = window.mapTranslations?.elSalvador || "El Salvador";

  mapModalBody.innerHTML = unis.map((uni, idx) => buildUniCard(uni, idx)).join('');
  mapModalOverlay.classList.add('open');
}'''

new_buildUniCard = '''function buildUniCard(uni, idx) {
  const careersHtml = uni.careers 
    ? uni.careers.split(',').map(c => `<span class="career-chip">${c.trim()}</span>`).join('') 
    : '';

  const mediaHtml = `<img src="${uni.image || 'https://images.unsplash.com/photo-1562774053-701939374585?w=400&q=80'}" alt="${uni.name}" onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?w=400&q=80'">`;

  const visitText = window.mapTranslations?.visitWebsite || "Visitar sitio web →";
  const careersLabel = window.mapTranslations?.careersLabel || "Carreras disponibles:";

  return `
  <div class="uni-modal-card">
    <div class="uni-card-top" style="flex-direction: column; gap: 1rem;">
      <div class="uni-media-wrap" style="width: 100%; height: 160px;">
        ${mediaHtml}
      </div>
      <div class="uni-card-info" style="width: 100%;">
        <div class="uni-card-name" style="font-size: 1.4rem;">${uni.name}</div>
        <p class="uni-card-desc" style="margin-top: 0.5rem; font-size: 0.95rem; color: var(--text-2);">${uni.description}</p>
        
        <div style="margin-top: 1.2rem;">
          <h5 style="color: var(--teal); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">${careersLabel}</h5>
          <div class="careers-grid" style="display:flex; flex-wrap:wrap; gap:0.5rem;">
            ${careersHtml}
          </div>
        </div>

        ${uni.website ? `<div style="margin-top: 1.5rem;">
          <a href="${uni.website}" target="_blank" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">${visitText}</a>
        </div>` : ''}
      </div>
    </div>
  </div>`;
}'''

# Replace openMapModal
content = re.sub(r'function openMapModal\(deptId\) \{.*?\n\}', new_openMapModal, content, flags=re.DOTALL)

# Replace buildUniCard
content = re.sub(r'function buildUniCard\(uni, .*?\) \{.*?\n\}', new_buildUniCard, content, flags=re.DOTALL)

with open('resources/js/homepage.js', 'w', encoding='utf-8') as f:
    f.write(content)
