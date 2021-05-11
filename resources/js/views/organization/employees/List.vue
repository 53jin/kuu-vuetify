<template>
    <kuu-container :breadcrumbs="breadcrumbs" :urls="urls">
        <kuu-data-table
            ref="table"
            :query="queries.list"
            :table-props="tableProps"
            :apiParams="filter_data"
        >
            <template #top>
                <v-row dense>
                    <v-col cols="6" md="4" lg="2">
                        <kuu-status-select v-model="filter_data.status"/>
                    </v-col>
                </v-row>
            </template>
            <template #item.related_user_id="{ item }">
                <span class="mr-1">{{ item.related_user_id }}</span>
                <v-btn
                    v-if="$canUpdate(item)"
                    class="hover-action" x-small icon
                    @click="showEditor(item[pk])"
                >
                    <v-icon>edit</v-icon>
                </v-btn>
                <v-tooltip top v-if="$canUpdate(item)">
                    <template #activator="{ on }">
                        <form
                            class="d-none" :ref="`alf:${item.related_user_id}`"
                            :action="url('login/'+item.related_user_id)"
                            method="POST" target="_blank"
                        >
                            <input type="hidden" name="_token" v-model="$root.csrfToken"/>
                        </form>
                        <v-btn
                            v-on="on" class="hover-action"
                            x-small icon type="submit"
                            @click="doAutoLogin(item)"
                        >
                            <v-icon>fas fa-sign-in-alt</v-icon>
                        </v-btn>
                    </template>
                    Login
                </v-tooltip>
            </template>
            <template #item.related_user.status="{ item }">
                <kuu-status
                    v-model="item.related_user.status" bool-value
                    @change="updateItem(item, {status: $event})"
                    :can-disable="$canDisable(item)" :can-enable="$canEnable(item)"/>
            </template>
            <template #item.roles="{ value }">
                <v-chip-group>
                    <v-chip color="primary" small v-for="role in value" :key="role.id">
                        {{ role.name }}
                    </v-chip>
                </v-chip-group>
            </template>
            <template #item.related_user.name="{ item }">
                <router-link
                    v-if="$canRead(item)"
                    :to="getDetailUrl(item)"
                >
                    {{ item.related_user.name }}
                </router-link>
                <template v-else>
                    {{ item.related_user.name }}
                </template>
            </template>
            <template #item.is_am="{ value }">
                <span>{{ value ? 'Yes' : 'No' }}</span>
            </template>
            <template #item.is_bd="{ value }">
                <span>{{ value ? 'Yes' : 'No' }}</span>
            </template>
        </kuu-data-table>
    </kuu-container>
</template>

<script>
    import editor from "./Editor";

    import Model from './model';
    import ModelList from "@kuu/mixins/model-list";

    export default {
        mixins: [Model, ModelList],
        data() {
            return {
                editor,
                filter_data: {
                    status: 1,
                }
            }
        },
        watch: {
            'filter_data.status'() {
                this.$nextTick(() => {
                    this.doRefreshTable();
                });
            }
        },
        computed: {
            tableProps() {
                return {
                    itemKey: this.pk,
                    sortBy: 'related_user_id',
                    sortDesc: true,
                    headers: [
                        {text: 'User ID', value: 'related_user_id', width: 120},
                        {text: 'Status', value: 'related_user.status'},
                        {text: 'User Name', value: 'related_user.name'},
                        {text: 'Roles', value: 'roles', sortable: false},
                        {text: 'Email', value: 'related_user.email'},
                        {text: 'Is AM', value: 'is_am'},
                        {text: 'Is BD', value: 'is_bd'},
                    ],
                };
            },
        },
        methods: {
            getDetailUrl(item) {
                return this.url(`organization/employees/${item.id}`);
            },
            doAutoLogin(item) {
                let ref = this.$refs['alf:' + item.related_user_id];
                ref && ref.submit();
            },
        },
    }
</script>
