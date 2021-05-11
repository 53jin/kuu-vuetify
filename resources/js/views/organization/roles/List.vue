<template>
    <kuu-container :breadcrumbs="breadcrumbs" :urls="urls">
        <kuu-data-table
            ref="table"
            :query="queries.list"
            :table-props="tableProps"
        >
            <template #item.id="{ item }">
                <span class="mr-1">{{ item[pk] }}</span>
                <v-btn
                    v-if="$canUpdate(item)"
                    class="hover-action" x-small icon
                    @click="showEditor(item[pk])"
                >
                    <v-icon>edit</v-icon>
                </v-btn>
            </template>
            <template #item.name="{ item }">
                <router-link
                    v-if="$canRead(item)"
                    :to="getDetailUrl(item)"
                >
                    {{ item.name }}
                </router-link>
                <template v-else>
                    {{ item.name }}
                </template>
            </template>
        </kuu-data-table>
    </kuu-container>
</template>

<script>
    import Model from './model'
    import ModelList from '@kuu/mixins/model-list'
    import editor from "./Editor"

    export default {
        mixins: [Model, ModelList],
        data() {
            return {
                editor,
            }
        },
        computed: {
            tableProps() {
                return {
                    itemKey: this.pk,
                    fixedHeader: true,
                    height: 600,
                    sortBy: this.pk,
                    sortDesc: true,
                    headers: [
                        {text: '#', value: 'id', width: 120},
                        {text: 'Name', value: 'name'},
                        {text: 'Created', value: 'created_at'},
                        {text: 'Updated', value: 'updated_at'},
                    ],
                };
            },
        },
        methods: {
            getDetailUrl(item) {
                return this.url(`organization/roles/${item[this.pk]}`);
            },
        },
    }
</script>
