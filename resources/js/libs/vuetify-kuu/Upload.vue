<template>
    <div class="d-inline-block">
        <input
            class="d-none" type="file" ref="file"
            :multiple="multiple" :accept="accept" :name="name" :disabled="disabled || loading"
            @change="onFileChange"
        />
        <div class="d-inline-block" @click="selectFile">
            <slot>
                <v-btn :disabled="disabled" :loading="loading" :color="color">
                    <v-icon left>mdi-cloud-upload</v-icon>
                    Upload
                </v-btn>
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            disabled: Boolean,
            loading: Boolean,
            multiple: Boolean,
            name: {type: String, default: 'file'},
            color: {type: String, default: 'primary'},
            action: String,
            csrfToken: String,
            accept: {},
            headers: Object,
            data: Object,
            clearFileOnUploaded: Boolean,
            isValid: {type: Boolean, default: true},

            mutation: {type: Object, default: null},
            variables: {type: Object, default: () => {}},
            form: {type: Object, default: null},
        },
        computed: {
            computedHeaders() {
                const vm = this;
                const ret = {
                    'Content-Type': 'multipart/form-data',
                };
                if (vm.csrfToken) {
                    ret['X-CSRF-TOKEN'] = vm.csrfToken;
                }

                return Object.assign({}, ret, vm.headers);
            },
        },
        methods: {
            selectFile() {
                this.$emit('on-validator', this.variables);
                this.$nextTick(() => {
                    if(this.isValid){
                        this.$refs.file.click();
                    }
                });
            },
            onFileChange($event) {
                const vm = this;
                const target = $event.target;
                vm.$emit('change', target);
                let files = target.files;
                if (!files || files.length === 0) {
                    return;
                }

                vm.$emit('before-upload');

                if (vm.mutation && vm.$mutate) {
                    let variables = _.cloneDeep(vm.variables || {});
                    variables[vm.name] = vm.multiple ? files : files[0];
                    vm.$mutate(vm.mutation, variables, {
                        form: vm.form,
                        success(data) {
                            vm.$emit('success', data);
                        },
                    }).finally(() => {
                        vm.onFinally();
                    });
                } else if (window.axios) {
                    let formData = new FormData();
                    for (let i = 0; i < files.length; i++) {
                        formData.append(vm.name, files[i]);
                    }
                    _.each(vm.data, (value, name) => {
                        formData.append(name, value)
                    });
                    axios.post(vm.action, formData, {
                        headers: vm.computedHeaders,
                        onUploadProgress(e) {
                            let percentage = Math.round((e.loaded * 100) / e.total) || 0;
                            vm.$emit('progress', percentage);
                        }
                    }).then(resp => {
                        vm.$emit('success', resp.data);
                    }).catch(error => {
                        vm.$emit('error', error);
                    }).finally(() => {
                        vm.onFinally();
                    });
                }
            },
            onFinally() {
                const vm = this;
                vm.$emit('finished');
                if (vm.clearFileOnUploaded) {
                    vm.$refs.file.value = '';
                }
            }
        },
    };
</script>
