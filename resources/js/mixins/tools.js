import vuetify from '@kuu/plugins/vuetify'
import apolloClient from '@kuu/plugins/apollo-client'
import VueApollo from "vue-apollo";

export default {
    methods: {
        mountComponent(component, options, element = '[data-app="true"]') {
            if (_.isString(element)) {
                element = document.querySelector(element);
            }
            if (!element) {
                return false;
            }
            options = options || {};
            options.vuetify = vuetify;
            options.apolloProvider =  new VueApollo({
                defaultClient: apolloClient(this.$root.csrfToken),
            });
            const componentInstance = new (Vue.extend(component))(options);
            componentInstance.$mount();
            element.appendChild(componentInstance.$el);
            return componentInstance;
        },
        parseDateTime(dateTime) {
            return dayjs(dateTime, 'YYYY-MM-DD HH:mm:ss');
        },
        url(path) {
            if (!_.startsWith(path, '/')) {
                path = '/' + path;
            }
            return path;
        },
        redirectToUrl(url) {
            this.$router.push(url);
        },
        // isMobile() {
        //     var isMobile = window.matchMedia("only screen and (max-width: 760px)");
        //
        //     return (isMobile.matches);
        // },
        appConfirmDelete(confirmCallback) {
            let text = "You will not be able to recover this data!";
            this.appConfirmWarning(text, confirmCallback);
        },
        async appConfirmWarning(text, confirmCallback, title = 'Are you sure?') {
            let res = await this.$dialog.warning({
                text: text,
                title: title,
            });
            if (res && _.isFunction(confirmCallback)) {
                confirmCallback();
            }
        },
        appResponseError(error) {
            let message = '';
            if (error) {
                if (error.message) {
                    message += error.message;
                }
                if (error.trace) {
                    message += "\n";
                    message += error.trace.map(v => JSON.stringify(v)).join("\n");
                }
            }
            if (!message) {
                message = 'Request Failed.';
            }
            this.appAlertError(message);
        },
        appAlertError(message) {
            this.$dialog.error({
                text: message,
                title: 'Oops!',
            });
        },
        appMessageSuccess(message) {
            this.$dialog.message.success(message, {
                position: 'top'
            })
        },
        appMessageError(message) {
            this.$dialog.message.error(message, {
                position: 'top'
            })
        },
    }
}
