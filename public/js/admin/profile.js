/* ═══════════════════════════════════════════════════════
   Avatar preview & auto-upload
═══════════════════════════════════════════════════════ */
function handleAvatarChange(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('ap-avatar-img').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    document.getElementById('ap-avatar-form').submit();
}

/* ═══════════════════════════════════════════════════════
   Password visibility toggle
═══════════════════════════════════════════════════════ */
function togglePw(fieldId, btn) {
    const input   = document.getElementById(fieldId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    btn.innerHTML = isHidden
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
               <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
           </svg>`;
}

/* ═══════════════════════════════════════════════════════
   Password strength meter
═══════════════════════════════════════════════════════ */
function checkStrength(val) {
    const fill  = document.getElementById('ap-strength-fill');
    const label = document.getElementById('ap-strength-label');

    if (!val) {
        fill.style.width  = '0%';
        label.textContent = '';
        return;
    }

    let score = 0;
    if (val.length >= 8)            score++;
    if (/[A-Z]/.test(val))          score++;
    if (/[a-z]/.test(val))          score++;
    if (/[0-9]/.test(val))          score++;
    if (/[^A-Za-z0-9]/.test(val))   score++;

    const levels = [
        { pct: '20%',  bg: 'var(--danger)', text: 'Very weak'   },
        { pct: '40%',  bg: 'var(--amber)',  text: 'Weak'        },
        { pct: '60%',  bg: 'var(--amber)',  text: 'Fair'        },
        { pct: '80%',  bg: 'var(--teal)',   text: 'Strong'      },
        { pct: '100%', bg: 'var(--green)',  text: 'Very strong' },
    ];

    const level           = levels[score - 1] || levels[0];
    fill.style.width      = level.pct;
    fill.style.background = level.bg;
    label.textContent     = level.text;
    label.style.color     = level.bg;
}

/* ═══════════════════════════════════════════════════════
   Modal — open / close
═══════════════════════════════════════════════════════ */
function openPasswordModal() {
    resetModal();
    document.getElementById('ap-pw-modal').classList.add('is-open');
    setTimeout(() => document.getElementById('modal-current-pw').focus(), 250);
}

function closePasswordModal() {
    document.getElementById('ap-pw-modal').classList.remove('is-open');
}

function handleBackdropClick(e) {
    if (e.target === document.getElementById('ap-pw-modal')) closePasswordModal();
}

// Close on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePasswordModal();
});

/* Reset modal back to step 1 */
function resetModal() {
    // Fields
    document.getElementById('modal-current-pw').value = '';
    document.getElementById('modal-new-pw').value      = '';
    document.getElementById('modal-confirm-pw').value  = '';

    // Strength bar
    const fill = document.getElementById('ap-strength-fill');
    if (fill) { fill.style.width = '0%'; }
    const lbl = document.getElementById('ap-strength-label');
    if (lbl)  { lbl.textContent  = ''; }

    // Error
    document.getElementById('ap-verify-error').style.display = 'none';

    // Steps
    showStep(1);
}

/* ═══════════════════════════════════════════════════════
   Step navigation helpers
═══════════════════════════════════════════════════════ */
function showStep(n) {
    document.getElementById('ap-modal-step-1').style.display = n === 1 ? '' : 'none';
    document.getElementById('ap-modal-step-2').style.display = n === 2 ? '' : 'none';
}

/* ═══════════════════════════════════════════════════════
   Step 1 — Verify current password via AJAX
═══════════════════════════════════════════════════════ */
async function verifyCurrentPassword() {
    const pw        = document.getElementById('modal-current-pw').value.trim();
    const errorBox  = document.getElementById('ap-verify-error');
    const errorText = document.getElementById('ap-verify-error-text');
    const btnText   = document.getElementById('ap-verify-btn-text');
    const btnLoad   = document.getElementById('ap-verify-btn-loading');
    const btn       = document.getElementById('ap-verify-btn');

    if (!pw) {
        errorText.textContent = 'Please enter your current password.';
        errorBox.style.display = 'flex';
        document.getElementById('modal-current-pw').focus();
        return;
    }

    // Loading state
    btnText.style.display = 'none';
    btnLoad.style.display = 'inline-flex';
    btn.disabled = true;
    errorBox.style.display = 'none';

    try {
        const res = await fetch(AP_VERIFY_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': AP_CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ current_password: pw }),
        });

        const data = await res.json();

        if (data.valid) {
            // Pass the verified password to the hidden field
            document.getElementById('ap-hidden-current-pw').value = pw;
            showStep(2);
            setTimeout(() => document.getElementById('modal-new-pw').focus(), 100);
        } else {
            errorText.textContent = data.message || 'Incorrect password. Please try again.';
            errorBox.style.display = 'flex';
            document.getElementById('modal-current-pw').select();
        }
    } catch (err) {
        errorText.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'flex';
    } finally {
        btnText.style.display = 'inline';
        btnLoad.style.display = 'none';
        btn.disabled = false;
    }
}