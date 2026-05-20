export function initCompare() {
  const slider = document.querySelector('[data-compare-slider]');
  const overlay = document.querySelector('[data-compare-overlay]');
  if (!slider || !overlay) return;

  const update = () => {
    const value = slider.value;
    overlay.style.width = `${value}%`;
  };

  slider.addEventListener('input', update);
  update();
}

initCompare();
