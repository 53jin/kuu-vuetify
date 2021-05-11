window._ = require('lodash');
_.mixin({
    assignPick(object, ...sources) {
        sources.forEach(source => {
            _.assign(object, _.pick(source, _.keys(object)));
        });

        return object;
    },
});
window.ClipboardJS = require('clipboard');
window.dayjs = require('dayjs');
window.dayjs.extend(require('dayjs/plugin/utc'));
window.humanizeDuration = require('humanize-duration');
window.humanizerShortEnglish = humanizeDuration.humanizer({
    language: 'shortEn',
    languages: {
        shortEn: {
            y: () => 'y',
            mo: () => 'mo',
            w: () => 'w',
            d: () => 'd',
            h: () => 'h',
            m: () => 'm',
            s: () => 's',
            ms: () => 'ms',
        }
    }
});

import Vue from 'vue'
window.Vue = Vue;
require('@kuu/plugins/vee-validate');

import formatLocalTime from './filters/formatLocalTime'
import nl2br from './filters/nl2br'
Vue.filter('nl2br', nl2br);
Vue.filter('formatLocalTime', formatLocalTime);

import MixinTools from './mixins/tools'
import MixinApollo from '@kuu/mixins/apollo'
Vue.mixin(MixinTools);
Vue.mixin(MixinApollo);

import { ValidationObserver, ValidationProvider } from "vee-validate";
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);

import Vuetify from 'vuetify'
Vue.use(Vuetify);

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.csrfToken = token.content;
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// window.axios = require('axios');
//
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Vue.prototype._ = window._;
Vue.prototype.$humanizeDuration = window.humanizeDuration;
document.addEventListener("wheel", function(event){
    if(document.activeElement.type === "number"){
        document.activeElement.blur();
    }
});

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
