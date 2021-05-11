
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import vuetify from '@kuu/plugins/vuetify'
import VueApollo from 'vue-apollo'
import apolloClient from '@kuu/plugins/apollo-client'
import VuetifyDialog from 'vuetify-dialog'
import VueDatePicker from '@mathieustan/vue-datepicker'
import VuetifyKuu from './libs/vuetify-kuu'
Vue.use(VueApollo);
Vue.use(VuetifyDialog, {context: { vuetify }});
Vue.use(VueDatePicker);
Vue.use(VuetifyKuu);

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('kuu-auth-login', require('@views/auth/Login').default);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient(window.csrfToken),
});
window.app = new Vue({
    vuetify,
    apolloProvider,
    el: '#app-wrapper',
    data(){
        return {
            csrfToken: window.csrfToken,
        }
    },
});
