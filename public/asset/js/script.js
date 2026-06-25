(function () {
    // ── Burger mobile ──────────────────────────────────────────
    const burger = document.querySelector('.nav-burger');
    const navList = document.getElementById('nav-list');

    if (burger && navList) {
        burger.addEventListener('click', function () {
            const open = navList.classList.toggle('is-open');
            burger.setAttribute('aria-expanded', open);
        });
    }

    // ── Dropdowns ──────────────────────────────────────────────
    const triggers = document.querySelectorAll('.nav-trigger');

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const item = trigger.closest('.nav-item');
            const isOpen = item.classList.contains('is-open');

            // Ferme tous les autres
            document.querySelectorAll('.nav-item.is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('.nav-trigger').setAttribute('aria-expanded', 'false');
            });

            // Bascule celui-ci
            if (!isOpen) {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Ferme au clic en dehors
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.nav-item')) {
            document.querySelectorAll('.nav-item.is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('.nav-trigger').setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Ferme à la touche Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.nav-item.is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('.nav-trigger').setAttribute('aria-expanded', 'false');
            });
        }
    });
})();