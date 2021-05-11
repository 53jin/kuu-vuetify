<template>
    <kuu-form ref="form" @submit.prevent="onFormSubmit" #default="{ invalid }">
        <kuu-editor-dialog
            v-model="visible"
            :loading="isLoading"
            :handing="isHandling"
            :disabled="isLoading || invalid"
            :title="title"
            @save="onFormSubmit"
        >
            <kuu-text-field
                v-model="input.first_name"
                label="First Name" counter="15" required
                rules="required|max:15"
            />

            <kuu-text-field
                v-model="input.last_name"
                label="Last Name" counter="15"
                rules="max:15"
            />

            <kuu-text-field
                v-if="!_.get(item, 'user.email_verified_at')"
                v-model="input.email"
                type="email" label="Email" counter="80" required
                rules="required|email|max:80"
            />

            <kuu-text-field
                v-model="input.password"
                label="Password"
                rules="passwordRule"
            />

            <v-select
                v-model="input.role_ids"
                label="Roles" multiple filterable clearable chips deletable-chips hide-details
                :items="roles"
                :loading="!roles"
                item-value="id" item-text="name"
            />

            <v-checkbox
                v-model="input.is_am" hide-details
                label="Is AM" :true-value="true" :false-value="false"
            />
            <v-checkbox
                v-model="input.is_bd"
                label="Is BD" :true-value="true" :false-value="false"
            />
        </kuu-editor-dialog>
    </kuu-form>
</template>

<script>
export default {
    mixins: [Model],
    data() {
        return {
            input: {
                first_name: null,
                last_name: null,
                email: null,
                password: null,
                is_am: false,
                is_bd: false,
                role_ids: [],
            },

            roles: [],
        }
    },
    mounted() {
        this.localId && this.find();
        this.fetchRoleData();
    },

    computed: {
        passwordRule() {
            console.log('passwordrule',this.localId ? 'min:8' : 'required|min:8')
            return this.localId ? 'min:8' : 'required|min:8'
        }
    },
    methods: {
        fetchRoleData() {
            const vm = this;
            vm.$query(GetAllRoles, ({ data }) => {
                vm.roles = data;
                console.log('11',vm.roles)
            });
        },
        assignInput(data) {
            _.assignPick(this.input, data, data.related_user);
            this.input.role_ids = _.map(data.roles, 'id');
        },
    },
}

    import { all as GetAllRoles } from '@gql/role.gql'

    import Model from './model'
</script>
