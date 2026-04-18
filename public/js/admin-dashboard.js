(function () {
    var el = document.getElementById('admin-dashboard-live-datetime');
    if (!el) {
        return;
    }

    function tick() {
        var d = new Date();
        var datePart = d.toLocaleDateString(undefined, {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        var timePart = d.toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        el.textContent = datePart + ' · ' + timePart;
    }

    tick();
    setInterval(tick, 1000);
})();
