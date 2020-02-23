// require("popper.js");
window.axios = require("axios");
// window.bsn = require("bootstrap.native/dist/bootstrap-native-v4");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

let userApiToken = document.head.querySelector('meta[name="user-api-token"]');

if (userApiToken) {
    window.axios.defaults.headers.common["Authorization"] =
        "Bearer " + userApiToken.content;
}

// window.bootstrap = require("bootstrap");
