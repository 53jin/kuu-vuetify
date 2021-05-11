<template>
    <layout>
        <v-card class="elevation-12">
            <kuu-form
                ref="form" @submit="isHandling = true" action="/login" method="post"
                #default="{ invalid, validated, passes, validate }"
            >
                <input type="hidden" name="_token" :value="$root.csrfToken"/>
                <input type="hidden" name="remember" :value="form_data.remember"/>
                <v-toolbar color="primary" dark flat>
                    <v-toolbar-title>Login</v-toolbar-title>
                    <v-spacer/>

                </v-toolbar>

                <v-card-text>
                    <kuu-text-field
                        label="Email" name="email" prepend-icon="person" type="email"
                        v-model="form_data.email" required rules="required|email"
                    />

                    <kuu-text-field
                        id="password" label="Password" name="password"
                        prepend-icon="lock" type="password"
                        v-model="form_data.password" required rules="required"
                    />
                </v-card-text>

                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        type="submit" color="primary"
                        :loading="isHandling" :disabled="invalid || !validated"
                    >Login</v-btn>
                </v-card-actions>
            </kuu-form>
        </v-card>
    </layout>
</template>

<script>
    import Layout from "./Layout";

    export default {
        props: {
            data: Object,
            errors: Boolean | Object,
        },
        data: () => ({
            isHandling: false,
            rememberCheckbox: true,
            form_data: {
                email: null,
                password: null,
                remember: 'yes',
            },
        }),
        mounted() {
            const vm = this;
            vm.form_data = Object.assign({}, vm.form_data, vm.data);
            vm.$nextTick(() => {
                vm.$refs.form.setErrors(vm.errors);
            });
        },
        watch:{
            rememberCheckbox(){
                this.form_data.remember = this.rememberCheckbox ? 'yes' : '';
            }
        },
        components: {
            Layout,
        },
    }
</script>
