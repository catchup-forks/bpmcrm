window._ = require("lodash");
window.Popper = require("popper.js").default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require("jquery");

require("bootstrap");

//const datetime_format = require("./data/datetime_formats.json");



/**
 * Create a axios instance which any vue component can bring in to call
 * REST api endpoints through oauth authentication
 *
 */
window.ProcessMaker.apiClient = require("axios");

window.ProcessMaker.apiClient.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector("meta[name=\"csrf-token\"]");

if (token) {
    window.ProcessMaker.apiClient.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}

//window.ProcessMaker.apiClient.defaults.baseURL = "/api/1.0/";
// Default to a 5 second timeout, which is an eternity in web app terms
//window.ProcessMaker.apiClient.defaults.timeout = 5000;

// Default alert functionality
/*
window.ProcessMaker.alert = function (text, variant) {
    window.alert(`${variant}: ${text}`);
};
*/




/*let userID = document.head.querySelector("meta[name=\"user-id\"]");
let formatDate = document.head.querySelector("meta[name=\"datetime-format\"]");
let timezone = document.head.querySelector("meta[name=\"timezone\"]");

if (userID) {
    window.ProcessMaker.user = {
        id: userID.content,
        datetime_format: formatDate.content,
        timezone: timezone.content
    };
    datetime_format.forEach(value => {
        if (formatDate.content === value.format) {
            window.ProcessMaker.user.datetime_format = value.momentFormat
        }
    });
}*/

/*let broadcaster = document.head.querySelector("meta[name=\"broadcaster\"]");
let key = document.head.querySelector("meta[name=\"broadcasting-key\"]");
let host = document.head.querySelector("meta[name=\"broadcasting-host\"]");

window.Echo = new Echo({
    broadcaster: broadcaster.content,
    key: key.content,
    host: host.content
});

if (userID) {
    window.Echo.private(`ProcessMaker.Models.User.${userID.content}`)
        .notification((token) => {
            ProcessMaker.pushNotification(token);
        });
}*/
