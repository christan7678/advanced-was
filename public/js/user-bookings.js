document.addEventListener('DOMContentLoaded', function () {

    const eventSelect = document.getElementById('eventSelect');
    const seatsInput = document.getElementById('seatsInput');
    const quantityDisplay = document.getElementById('quantityDisplay');
    const totalPriceDisplay = document.getElementById('totalPrice');
    const totalAmountInput = document.getElementById('totalAmountInput');
    const displayTotalAmount = document.getElementById('displayTotalAmount');
    const unitPriceDisplay = document.getElementById('unitPriceDisplay');
    const priceContainer = document.getElementById('priceContainer');
    const seatInfo = document.getElementById('seatInfo');

    let currentEventPrice = 0;

    function formatCurrency(value) {
        return 'RM' + parseFloat(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateEventPrice() {
        if (!eventSelect) return;

        const selectedOption = eventSelect.options[eventSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            priceContainer.style.display = 'none';
            return;
        }

        currentEventPrice = parseFloat(selectedOption.dataset.price) || 0;
        const maxSeats = parseInt(selectedOption.dataset.available) || 0;

        // Update max seats
        if (seatsInput) {
            seatsInput.max = maxSeats;
            seatsInput.value = 1;
        }

        // Show available seats
        if (seatInfo) {
            seatInfo.textContent = 'Max ' + maxSeats + ' seats available';
        }

        // Show price container
        if (priceContainer) {
            priceContainer.style.display = 'block';
        }

        updatePrice();
    }

    function updatePrice() {
        if (!seatsInput) return;

        let quantity = parseInt(seatsInput.value) || 1;
        const max = parseInt(seatsInput.max) || 1;

        if (quantity > max) {
            quantity = max;
            seatsInput.value = quantity;
        }

        if (quantity < 1) {
            quantity = 1;
            seatsInput.value = 1;
        }

        const total = (quantity * currentEventPrice).toFixed(2);

        if (quantityDisplay) {
            quantityDisplay.textContent = quantity;
        }

        if (unitPriceDisplay) {
            unitPriceDisplay.textContent = formatCurrency(currentEventPrice);
        }

        if (totalPriceDisplay) {
            totalPriceDisplay.textContent = formatCurrency(total);
        }

        if (totalAmountInput) {
            totalAmountInput.value = total;
        }

        if (displayTotalAmount) {
            displayTotalAmount.value = formatCurrency(total);
        }
    }

    function checkSoldOut() {
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) return;

        const available = parseInt(selectedOption.dataset.available) || 0;

        if (available <= 0) {
            priceContainer.style.display = 'none';
            seatInfo.textContent = '❌ Sold Out';
            seatsInput.disabled = true;
        } else {
            seatsInput.disabled = false;
            updateEventPrice();
        }
    }

    // ===== Event Listeners =====
    if (eventSelect && eventSelect.value) {
        updateEventPrice();
    } else {
        const eventData = document.getElementById('eventData');

        if (eventData) {
            currentEventPrice = parseFloat(eventData.dataset.price) || 0;
            const maxSeats = parseInt(eventData.dataset.available) || 0;

            if (seatsInput) {
                seatsInput.max = maxSeats;
            }

            if (seatInfo) {
                seatInfo.textContent = 'Max ' + maxSeats + ' seats available';
            }

            if (priceContainer) {
                priceContainer.style.display = 'block';
            }

            updatePrice();
        }
    }

    if (eventSelect) {
        eventSelect.addEventListener('change', function () {

            const selectedOption = eventSelect.options[eventSelect.selectedIndex];
            const available = parseInt(selectedOption.dataset.available) || 0;

            const ticketSection = document.getElementById('ticketSection');
            const submitBtn = document.getElementById('submitBtn');
            const soldOutBox = document.getElementById('soldOutBox');

            if (available <= 0) {
                if (soldOutBox) soldOutBox.style.display = 'block';
                if (ticketSection) ticketSection.style.display = 'none';
                if (priceContainer) priceContainer.style.display = 'none';
                if (submitBtn) submitBtn.style.display = 'none';

                seatsInput.disabled = true;
                return;
            }

            if (soldOutBox) soldOutBox.style.display = 'none';
            if (ticketSection) ticketSection.style.display = 'block';
            if (priceContainer) priceContainer.style.display = 'block';
            if (submitBtn) submitBtn.style.display = 'block';

            seatsInput.disabled = false;

            updateEventPrice();
        });
    }

    if (seatsInput) {
        seatsInput.addEventListener('input', updatePrice);
    }
});