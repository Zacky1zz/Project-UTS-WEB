'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Toggle menu mobile
    const toggle = document.getElementById('navToggle');
    const nav = document.getElementById('mainNav');
    toggle?.addEventListener('click', () => {
        const open = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(open));
    });

    // 2. Filter katalog secara live (tanpa reload)
    const search = document.getElementById('search');
    const category = document.getElementById('category');
    const cards = [...document.querySelectorAll('#grid .card')];
    const count = document.getElementById('count');
    const empty = document.getElementById('empty');

    const applyFilter = () => {
        const q = (search?.value ?? '').trim().toLowerCase();
        const c = category?.value ?? '';
        let visible = 0;
        cards.forEach(card => {
            const show = card.dataset.search.includes(q) && (c === '' || card.dataset.category === c);
            card.hidden = !show;
            if (show) visible++;
        });
        if (count) count.textContent = visible;
        if (empty) empty.hidden = visible > 0;
    };
    if (cards.length) {
        search?.addEventListener('input', applyFilter);
        category?.addEventListener('change', applyFilter);
        applyFilter();
    }

    // 3. Validasi form di sisi klien
    const messages = {
        valueMissing: 'Kolom ini wajib diisi.',
        typeMismatch: 'Format tidak valid.',
        tooShort: 'Terlalu pendek.',
        rangeUnderflow: 'Nilai terlalu kecil.',
        rangeOverflow: 'Nilai terlalu besar.',
    };

    const validateField = input => {
        const field = input.closest('.field');
        const out = field?.querySelector('.error-text');
        let msg = '';

        if (!input.validity.valid) {
            const key = Object.keys(messages).find(k => input.validity[k]);
            msg = messages[key] ?? 'Input tidak valid.';
            if (input.validity.tooShort) msg = `Minimal ${input.minLength} karakter.`;
        } else if (input.dataset.match) {
            const other = document.getElementById(input.dataset.match);
            if (other && other.value !== input.value) msg = 'Konfirmasi tidak cocok.';
        }

        field?.classList.toggle('invalid', msg !== '');
        if (out) out.textContent = msg;
        return msg === '';
    };

    document.querySelectorAll('form[data-validate]').forEach(form => {
        const inputs = [...form.querySelectorAll('input, select, textarea')]
            .filter(i => i.type !== 'hidden');
        inputs.forEach(i => i.addEventListener('blur', () => validateField(i)));
        form.addEventListener('submit', ev => {
            const allValid = inputs.map(validateField).every(Boolean);
            if (!allValid) {
                ev.preventDefault();
                form.querySelector('.invalid input')?.focus();
            }
        });
    });

    // 4. Indikator kekuatan password
    const pw = document.querySelector('input[name="password"][minlength]');
    const bar = document.getElementById('meterBar');
    pw?.addEventListener('input', () => {
        const v = pw.value;
        let score = 0;
        if (v.length >= 6) score++;
        if (v.length >= 10) score++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
        if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) score++;
        bar.style.width = `${(score / 4) * 100}%`;
        bar.style.background = ['#c92a2a', '#e67700', '#f59f00', '#2b8a3e'][Math.max(score - 1, 0)];
    });

    // 5. Konfirmasi aksi berbahaya
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', ev => {
            if (!confirm(form.dataset.confirm)) ev.preventDefault();
        });
    });

    // 6. Pesan flash hilang otomatis
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => { a.style.opacity = '0'; setTimeout(() => a.remove(), 400); }, 5000);
    });
});
