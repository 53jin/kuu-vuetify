<template>
    <ValidationObserver ref="vos" #default="props">
        <v-form ref="form" v-on="$listeners" v-bind="$attrs">
            <slot v-bind="props"/>
            <input ref="submit" type="submit" class="d-none"/>
        </v-form>
    </ValidationObserver>
</template>

<script>
    export default {
        props: {
            inputName: {
                type: String,
                default: 'input',
            },
        },
        methods: {
            submit() {
                this.$refs.submit.click();
            },
            setErrors(errors) {
                const vm = this;
                const errs = {};
                _.each(errors, (values, key) => {
                    if (vm.inputName) {
                        let p = vm.inputName+'.';
                        if (key.indexOf(p) === 0) {
                            key = key.slice(p.length);
                        }
                        values.forEach((value, index) => {
                            if (value.indexOf(p) > -1) {
                                value = _.replace(value, new RegExp(p, 'g'), '');
                            }
                            values[index] = value;
                        });

                        errs[key] = values;
                    }
                });

                vm.$nextTick(() => {
                    vm.$refs.vos.setErrors(errs);
                });
            },
        }
    };
</script>
