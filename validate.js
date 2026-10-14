// Client-side validation for the registration form
const form = document.getElementById('regForm');
const MAX_SIZE = 2 * 1024 * 1024; // 2 MB

// Each rule returns an error message, or "" if the value is valid
const rules = {
  name:    v => v.trim().length < 3 ? 'Name must be at least 3 characters' : '',
  roll_no: v => !/^[0-9]{2}[A-Za-z]{2,4}[0-9]{2,3}$/.test(v.trim()) ? 'Format like 25CSL06' : '',
  email:   v => !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? 'Enter a valid email address' : '',
  course:  v => v === '' ? 'Please select a course' : '',
  password: v => passwordScore(v) < 4
      ? 'Min 8 chars with uppercase, lowercase, digit and special character' : '',
};

// Password strength: one point per satisfied criterion (0-4); length>=8 is mandatory
function passwordScore(p) {
  if (p.length < 8) return 0;
  return [/[a-z]/, /[A-Z]/, /\d/, /[^A-Za-z0-9]/].filter(r => r.test(p)).length;
}

function fileRule(input) {
  const f = input.files[0];
  if (!f) return 'Please choose an image';
  if (!['image/jpeg', 'image/png'].includes(f.type)) return 'Only JPG or PNG allowed';
  if (f.size > MAX_SIZE) return 'File must be 2 MB or smaller';
  return '';
}

function show(input, msg) {
  const box = input.closest('.field');
  box.querySelector('.error').textContent = msg;
  box.classList.toggle('invalid', !!msg);
  box.classList.toggle('valid', !msg);
  return !msg;
}

function check(input) {
  const msg = input.type === 'file' ? fileRule(input) : rules[input.name](input.value);
  return show(input, msg);
}

const inputs = [...form.querySelectorAll('input, select')];
inputs.forEach(i => i.addEventListener('blur', () => check(i)));   // validate on leaving a field
document.getElementById('password').addEventListener('input', e => {
  document.getElementById('strength').value = passwordScore(e.target.value);
});

form.addEventListener('submit', e => {
  const allOk = inputs.map(check).every(Boolean);  // map first so every field shows its error
  if (!allOk) e.preventDefault();                  // block submit if anything is invalid
});
