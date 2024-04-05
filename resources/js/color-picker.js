import 'vanilla-colorful/hex-color-picker.js';
import 'vanilla-colorful/hex-input.js';

const picker = document.querySelector('hex-color-picker');
const input = document.querySelector('hex-input');
const hidden = document.querySelector('input[name="color"]');

if (picker && input) picker.addEventListener('color-changed', (event) => {
  input.color = event.detail.value;
  if (hidden) hidden.value = event.detail.value;
});

if (input && picker) input.addEventListener('color-changed', (event) => {
  picker.color = event.detail.value;
  if (hidden) hidden.value = event.detail.value;
});