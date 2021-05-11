const resolveApolloConfig = (variables, options) => {
    const ret = {};

    if (_.isFunction(variables)) {
        ret.success = variables;
    } else {
        ret.variables = variables;
    }
    if (!ret.success && _.isFunction(options)) {
        ret.success = options;
    }
    if (_.isObject(options)) {
        ret.form = options.form;
        if (!ret.success) {
            ret.success = options.success;
        }
    }

    return ret;
};

export default {
    methods: {
        $query(query, variables, options) {
            const vm = this;

            const config = resolveApolloConfig(variables, options);
            return vm.$apollo.query({
                query, variables: config.variables,
                fetchPolicy: 'no-cache',
            }).then(({ data }) => {
                _.isFunction(config.success) && config.success(data);
            }).catch(error => {
                vm.$apolloError(error, config.form);
            });
        },
        $mutate(mutation, variables, options) {
            const vm = this;

            const config = resolveApolloConfig(variables, options);
            return vm.$apollo.mutate({
                mutation, variables: config.variables,
                fetchPolicy: 'no-cache',
            }).then(({ data }) => {
                _.isFunction(config.success) && config.success(data);
            }).catch(error => {
                vm.$apolloError(error, config.form);
            });
        },
        $apolloError(error, $form) {
            if (!error) {
                return;
            }

            if (error.networkError &&
                error.networkError.result &&
                error.networkError.result.message &&
                error.networkError.result.message.indexOf('CSRF token mismatch') > -1) {
                this.appMessageError('Session is expires.');
                !window.sessionExpires && window.location.reload();
                window.sessionExpires = true;
            }

            if (error.graphQLErrors && error.graphQLErrors.length > 0) {
                error.graphQLErrors.forEach(e => {
                    if (e.message === 'validation') {
                        $form && $form.setErrors(e.extensions.validation);
                    } else {
                        this.appResponseError(e);
                    }
                });
            }
        },
    },
}
