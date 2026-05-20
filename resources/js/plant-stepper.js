document.addEventListener('DOMContentLoaded', () => {
  const stepper = document.querySelector('[data-stepper]');
  if (!stepper) return;

  const panes = Array.from(stepper.querySelectorAll('[data-step-pane]'));
  const indicators = Array.from(stepper.querySelectorAll('[data-step-indicator]'));
  let currentStep = 1;

  const showStep = (step) => {
    currentStep = step;
    panes.forEach((pane) => {
      pane.classList.toggle('d-none', Number(pane.dataset.stepPane) !== step);
    });
    indicators.forEach((indicator) => {
      indicator.classList.toggle('active', Number(indicator.dataset.stepIndicator) === step);
    });
  };

  const validateStep = (step) => {
    const pane = panes.find((p) => Number(p.dataset.stepPane) === step);
    if (!pane) return true;

    let valid = true;
    pane.querySelectorAll('[data-step-required]').forEach((el) => {
      if (!el.value) {
        valid = false;
        el.classList.add('is-invalid');
      } else {
        el.classList.remove('is-invalid');
      }
    });

    return valid;
  };

  stepper.querySelectorAll('[data-step-action]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const action = btn.dataset.stepAction;
      if (action === 'next') {
        if (!validateStep(currentStep)) return;
        showStep(currentStep + 1);
      } else if (action === 'prev') {
        showStep(currentStep - 1);
      }
    });
  });

  showStep(currentStep);

  // External API search
  const searchInput = stepper.querySelector('[data-external-strain-search]');
  const results = stepper.querySelector('[data-external-strain-results]');
  const selected = stepper.querySelector('[data-external-strain-selected]');
  const preview = stepper.querySelector('[data-external-strain-image]');
  const hiddenId = stepper.querySelector('[name="external_strain_id"]');
  const hiddenName = stepper.querySelector('[name="external_strain_name"]');
  const hiddenImage = stepper.querySelector('[name="external_strain_image_url"]');

  let searchTimeout = null;

  const renderResults = (items) => {
    results.innerHTML = '';
    if (!items.length) {
      results.classList.add('d-none');
      return;
    }

    items.forEach((item) => {
      const row = document.createElement('button');
      row.type = 'button';
      row.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2';
      row.innerHTML = `
        ${item.image_url ? `<img src="${item.image_url}" alt="" class="external-plant-thumb" />` : '<span class="external-plant-thumb placeholder"></span>'}
        <div class="text-start">
          <div class="fw-semibold">${item.name}</div>
          ${item.scientific_name ? `<div class="small text-secondary">${item.scientific_name}</div>` : ''}
        </div>
      `;
      row.addEventListener('click', () => {
        hiddenId.value = item.id || '';
        hiddenName.value = item.name || '';
        hiddenImage.value = item.image_url || '';
        selected.textContent = item.name || '—';
        if (preview) {
          if (item.image_url) {
            preview.src = item.image_url;
            preview.classList.remove('d-none');
          } else {
            preview.classList.add('d-none');
          }
        }
        results.classList.add('d-none');
      });
      results.appendChild(row);
    });

    results.classList.remove('d-none');
  };

  const searchPlants = async (query) => {
    if (!query) {
      results.classList.add('d-none');
      return;
    }
    try {
      const response = await fetch(`/api/external/plants?q=${encodeURIComponent(query)}`);
      const data = await response.json();
      renderResults(data.data || []);
    } catch (e) {
      results.classList.add('d-none');
    }
  };

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        searchPlants(searchInput.value.trim());
      }, 300);
    });
  }

  // Photo mode
  const photoRadios = stepper.querySelectorAll('[name="photo_mode"]');
  const uploadBlock = stepper.querySelector('[data-photo-upload]');

  photoRadios.forEach((radio) => {
    radio.addEventListener('change', () => {
      if (!uploadBlock) return;
      uploadBlock.classList.toggle('d-none', radio.value !== 'upload' || !radio.checked);
    });
  });
});
