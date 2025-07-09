window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

!(function (l) {
    "use strict";
    l("#sidebarToggle, #sidebarToggleTop").on("click", function (e) {
        l("body").toggleClass("sidebar-toggled"),
            l(".sidebar").toggleClass("toggled"),
            l(".sidebar").hasClass("toggled") &&
                l(".sidebar .collapse").collapse("hide");
    }),
        l(window).resize(function () {
            l(window).width() < 768 && l(".sidebar .collapse").collapse("hide"),
                l(window).width() < 480 &&
                    !l(".sidebar").hasClass("toggled") &&
                    (l("body").addClass("sidebar-toggled"),
                    l(".sidebar").addClass("toggled"),
                    l(".sidebar .collapse").collapse("hide"));
        }),
        l("body.fixed-nav .sidebar").on(
            "mousewheel DOMMouseScroll wheel",
            function (e) {
                var o;
                768 < l(window).width() &&
                    ((o = (o = e.originalEvent).wheelDelta || -o.detail),
                    (this.scrollTop += 30 * (o < 0 ? 1 : -1)),
                    e.preventDefault());
            }
        ),
        l(document).on("scroll", function () {
            100 < l(this).scrollTop()
                ? l(".scroll-to-top").fadeIn()
                : l(".scroll-to-top").fadeOut();
        }),
        l(document).on("click", "a.scroll-to-top", function (e) {
            var o = l(this);
            l("html, body")
                .stop()
                .animate(
                    { scrollTop: l(o.attr("href")).offset().top },
                    1e3,
                    "easeInOutExpo"
                ),
                e.preventDefault();
        });
})(jQuery);
