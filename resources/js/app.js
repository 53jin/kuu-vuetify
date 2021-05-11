require('./bootstrap');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import vuetify from '@kuu/plugins/vuetify'
import VueApollo from 'vue-apollo'
import VuetifyDialog from 'vuetify-dialog'
import VueDatePicker from '@mathieustan/vue-datepicker'
import VuetifyKuu from '@kuu'
Vue.use(VueApollo);
Vue.use(VuetifyDialog, {context: {vuetify}});
Vue.use(VueDatePicker);
Vue.use(VuetifyKuu);

import MixInPermission from '@/mixins/permission'
Vue.mixin(MixInPermission);

import { getRoutes } from './helpers/router';

import { FindMeAndApp } from '@/graphQL/user.gql';

import App from '@views/App'

import apolloClient from "@kuu/plugins/apollo-client";
const apolloProvider = new VueApollo({
    defaultClient: apolloClient(window.csrfToken),
});

new Vue({ apolloProvider, vuetify }).$query(FindMeAndApp, {withApp: true}, ({ me, app }) => {
    me.isEmployee = !!me.employee;
    const routes = getRoutes(me);
    routes.push({
        path: '/',
        component: Vue.extend({
            template: '<i/>',
            created() {
                const vm = this;
                const normalizeRoute = routes.find(value => {
                    return !value.path.match(/[:*]/);
                });
                vm.redirectToUrl(vm.url(normalizeRoute ? normalizeRoute.path : '404'));
            },
        }),
    });
    const router = new VueRouter({
        mode: 'history',
        routes,
    });

    window.app = new Vue({
        router,
        vuetify,
        apolloProvider,
        el: '#app-wrapper',
        data() {
            return {
                routes,
                user: me,
                app,
                csrfToken: window.csrfToken,
            }
        },
        render: (h) => h(App),
    });
});
