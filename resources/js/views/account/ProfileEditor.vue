<template>
    <kuu-form ref="form" @submit.prevent="onFormSubmit">
        <v-text-field
            v-model="userFormData.first_name"
            label="First Name" counter="25" required
        />

        <v-text-field
            v-model="userFormData.last_name"
            label="Last name" counter="25" required
        />

        <v-text-field
            v-model="userFormData.password"
            type="password"
            label="Password"
        />
        <v-btn color="primary" type="submit" :loading="isHandling" v-if="publisherDetail">Save</v-btn>
        <button type="submit" ref="submit" class="d-none"/>

    </kuu-form>
</template>

<script>
    import { FindMeAndApp, GetImTypes, UpdateUser } from '@gql/user.gql'

    export default {
        props: [
            'id', 'password'
        ],
        data() {
            return {
                isLoading: false,
                isHandling: false,
                userFormData: {
                    first_name: null,
                    last_name: null,
                    password: null
                },
                userData: {
                    email: null,
                },
            }
        },
        computed: {
            publisherDetail() {
                return !this.id
            },
        },
        watch: {
            isLoading(val) {
                this.$emit('loading', val);
            },
            isHandling(val) {
                this.$emit('handing', val);
            },
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            submit() {
                this.$refs.submit.click();
            },

            onItemSaved() {
                this.appMessageSuccess('Successfully saved.');
                this.redirectToUrl('account');
            },

            fetchData() {
                const vm = this;
                vm.isLoading = true;
                vm.$query(FindMeAndApp, {withApp: false}, ({ me }) => {
                    vm.userFormData = _.assignPick(vm.userFormData, me);
                    vm.userData = _.assignPick(vm.userData, me);
                }).finally(_ => vm.isLoading = false);
            },
            onFormSubmit() {
                const vm = this;
                vm.isHandling = true;
                vm.$mutate(UpdateUser, {data: vm.userFormData}, {
                    form: vm.$refs.form,
                    success() {
                        vm.appMessageSuccess('Successfully saved.');
                        vm.$emit('saved')
                    },
                }).finally(() => vm.isHandling = false);
            },
        },
    }
</script>
