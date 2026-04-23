document.addEventListener('DOMContentLoaded', function () {
    const paymentMethod = document.getElementById('paymentMethod');
    const cardSection = document.getElementById('cardSection');
    const paymentForm = document.getElementById('paymentForm');
    const expiryInput = document.getElementById('expiryInput');
    const expiryError = document.getElementById('expiryError');

    const cardNumber = document.getElementById('cardNumber');
    const cardNumberError = document.getElementById('cardNumberError');

    const cardName = document.getElementById('cardName');
    const cardNameError = document.getElementById('cardNameError');

    const cvv = document.getElementById('cvv');
    const cvvError = document.getElementById('cvvError');

    const bookingExpiry = window.bookingData ? window.bookingData.expiry : null;
    const expiryTime = bookingExpiry ? new Date(bookingExpiry).getTime() : null;

    // =========================
    // Countdown
    // =========================
   function updateCountdown() {
        if (!expiryTime) return;

        const now = new Date().getTime();
        const distance = expiryTime - now;

        const el = document.getElementById("countdown");
        const btn = document.querySelector('button[type="submit"]');

        if (!el || !btn) return;

        if (distance <= 0) {
            el.innerHTML = "EXPIRED";
            btn.disabled = true;
            btn.textContent = "Expired";
            btn.classList.remove('btn-success');
            btn.classList.add('btn-secondary');
            return;
        }

        const minutes = Math.floor(distance / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        el.innerHTML = minutes + "m " + seconds + "s";
    }
    
    // =========================
    // Generic error helpers
    // =========================
    function showFieldError(input, errorEl, message) {
        if (!input || !errorEl) return;
        input.classList.add('is-invalid');
        errorEl.textContent = message;
    }

    function clearFieldError(input, errorEl) {
        if (!input || !errorEl) return;
        input.classList.remove('is-invalid');
        errorEl.textContent = '';
    }

    // =========================
    // Expiry helpers
    // =========================
    function showExpiryError(message) {
        showFieldError(expiryInput, expiryError, message);
    }

    function clearExpiryError() {
        clearFieldError(expiryInput, expiryError);
    }

    // =========================
    // Toggle payment method
    // =========================
    function toggleMethod() {
        if (!paymentMethod || !cardSection) return;

        const method = paymentMethod.value;
        const cardInputs = cardSection.querySelectorAll('input');

        if (method === 'card') {
            cardSection.style.display = 'block';
            cardInputs.forEach(input => input.setAttribute('required', 'required'));
        } else {
            cardSection.style.display = 'none';
            cardInputs.forEach(input => {
                input.removeAttribute('required');
                input.classList.remove('is-invalid');
            });

            clearFieldError(cardNumber, cardNumberError);
            clearFieldError(cardName, cardNameError);
            clearFieldError(cvv, cvvError);
            clearExpiryError();
        }
    }

    if (paymentMethod) {
        paymentMethod.addEventListener('change', toggleMethod);
    }

    // =========================
    // Validate expiry
    // =========================
    function validateExpiryValue() {
        if (!expiryInput) return true;

        const value = expiryInput.value.trim();

        if (!value) {
            showExpiryError('Expiry date is required');
            return false;
        }

        if (!/^\d{2}\/\d{2}$/.test(value)) {
            showExpiryError('Format must be MM/YY');
            return false;
        }

        const [month, year] = value.split('/');
        const monthNum = parseInt(month, 10);
        const yearNum = parseInt(year, 10);

        if (isNaN(monthNum) || isNaN(yearNum)) {
            showExpiryError('Invalid expiry date');
            return false;
        }

        if (monthNum < 1 || monthNum > 12) {
            showExpiryError('Month must be between 01 and 12');
            return false;
        }

        const now = new Date();
        const currentYear = now.getFullYear() % 100;
        const currentMonth = now.getMonth() + 1;

        if (yearNum < currentYear || (yearNum === currentYear && monthNum < currentMonth)) {
            showExpiryError('Card has expired');
            return false;
        }

        clearExpiryError();
        return true;
    }

    function initExpiryValidation() {
        if (!expiryInput) return;

        expiryInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 4);

            if (value.length >= 3) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }

            e.target.value = value;
            clearExpiryError();
        });

        expiryInput.addEventListener('blur', function () {
            validateExpiryValue();
        });
    }

    // =========================
    // Validate card number
    // =========================
    function validateCardNumberValue() {
        if (!cardNumber) return true;

        const value = cardNumber.value.replace(/\s/g, '');

        if (!value) {
            showFieldError(cardNumber, cardNumberError, 'Card number is required');
            return false;
        }

        if (!/^\d{16}$/.test(value)) {
            showFieldError(cardNumber, cardNumberError, 'Card number must be 16 digits');
            return false;
        }

        clearFieldError(cardNumber, cardNumberError);
        return true;
    }

    function initCardNumberValidation() {
        if (!cardNumber) return;

        cardNumber.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 16);
            value = value.replace(/(.{4})/g, '$1 ').trim();

            e.target.value = value;
            clearFieldError(cardNumber, cardNumberError);
        });

        cardNumber.addEventListener('blur', function () {
            validateCardNumberValue();
        });
    }

    // =========================
    // Validate card holder name
    // =========================
    function validateCardNameValue() {
        if (!cardName) return true;

        const value = cardName.value.trim();

        if (!value) {
            showFieldError(cardName, cardNameError, 'Card holder name is required');
            return false;
        }

        if (!/^[A-Za-z ]+$/.test(value)) {
            showFieldError(cardName, cardNameError, 'Only letters and spaces allowed');
            return false;
        }

        if (value.length < 2) {
            showFieldError(cardName, cardNameError, 'Name is too short');
            return false;
        }

        clearFieldError(cardName, cardNameError);
        return true;
    }

    function initCardNameValidation() {
        if (!cardName) return;

        cardName.addEventListener('input', function () {
            clearFieldError(cardName, cardNameError);
        });

        cardName.addEventListener('blur', function () {
            validateCardNameValue();
        });
    }

    // =========================
    // Validate CVV
    // =========================
    function validateCVVValue() {
        if (!cvv) return true;

        const value = cvv.value.trim();

        if (!value) {
            showFieldError(cvv, cvvError, 'CVV is required');
            return false;
        }

        if (!/^\d{3}$/.test(value)) {
            showFieldError(cvv, cvvError, 'CVV must be 3 digits');
            return false;
        }

        clearFieldError(cvv, cvvError);
        return true;
    }

    function initCVVValidation() {
        if (!cvv) return;

        cvv.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 3);
            e.target.value = value;

            clearFieldError(cvv, cvvError);
        });

        cvv.addEventListener('blur', function () {
            validateCVVValue();
        });
    }

    // =========================
    // Submit validation
    // =========================
    if (paymentForm) {
        paymentForm.addEventListener('submit', function (e) {
            if (!paymentMethod) return;

            if (paymentMethod.value === 'card') {
                const isCardNumberValid = validateCardNumberValue();
                const isCardNameValid = validateCardNameValue();
                const isCVVValid = validateCVVValue();
                const isExpiryValid = validateExpiryValue();

                if (!isCardNumberValid || !isCardNameValid || !isCVVValid || !isExpiryValid) {
                    e.preventDefault();
                }
            }
        });
    }

    // =========================
    // Init
    // =========================
    if (expiryTime) {
        setInterval(updateCountdown, 1000);
        updateCountdown();
    }

    initCardNumberValidation();
    initCardNameValidation();
    initCVVValidation();
    initExpiryValidation();
    toggleMethod();
});