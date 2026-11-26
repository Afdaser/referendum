(function () {
    'use strict';

    var analytics = (window.rfrndm && window.rfrndm.analytics) || {};
    var measurementId = analytics.ga4MeasurementId || '';
    var active = false;
    var pendingKey = 'referendum_analytics_next_event';

    function track(name, details) {
        if (active && typeof window.gtag === 'function') {
            window.gtag('event', name, details || {});
        }
    }

    function trackAfterNavigation(name, details) {
        try {
            window.sessionStorage.setItem(pendingKey, JSON.stringify({name: name, details: details || {}}));
        } catch (error) {
            track(name, details);
        }
    }

    window.referendumAnalytics = {track: track, trackAfterNavigation: trackAfterNavigation};

    function start() {
        if (active || !/^G-[A-Z0-9]+$/.test(measurementId)) {
            return;
        }
        active = true;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', measurementId);

        var script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId);
        document.head.appendChild(script);

        var poll = location.pathname.match(/^\/poll\/(\d+)\/?$/);
        if (poll) {
            track('view_poll', {poll_id: poll[1]});
        }
        if (/^\/tag\/[^/]+\/?$/.test(location.pathname)) {
            track('view_poll_topic');
        }
        if (/^\/news\/[^/]+\/?$/.test(location.pathname)) {
            track('view_news');
        }
        try {
            var pending = window.sessionStorage.getItem(pendingKey);
            window.sessionStorage.removeItem(pendingKey);
            if (pending) {
                var event = JSON.parse(pending);
                if (event && /^[a-z][a-z0-9_]*$/.test(event.name)) {
                    track(event.name, event.details);
                }
            }
        } catch (error) {
            // Сховище браузера може бути недоступним; перегляд сторінки все одно рахується.
        }
    }

    start();

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (form.matches('.poll-option-form')) {
            track('poll_vote_submit');
        } else if (form.matches('form[action*="addComment"], form[action*="add-comment"]')) {
            track('comment_submit');
        } else if (form.matches('form[action*="site/search"]')) {
            track('search_submit');
        } else if (form.matches('#header-login-form, form[action*="login.html"]')) {
            track('login_submit');
        } else if (form.matches('#registrtion_step_1 form')) {
            track('registration_submit');
        } else if (form.matches('form[action*="createPoll"]')) {
            track('poll_create_submit');
        }
    });
    document.addEventListener('change', function (event) {
        var control = event.target;
        if (control.matches('.vote-region, .gender, .age, .registration')) {
            track('poll_results_filter', {filter_type: control.className.split(' ')[0]});
        }
    });
    document.addEventListener('click', function (event) {
        var button = event.target.closest('.links_share .icon_share');
        if (button) {
            track('share', {method: button.classList.contains('facebook') ? 'facebook'
                : button.classList.contains('twitter') ? 'x' : 'copy_link'});
        }
    });
})();
