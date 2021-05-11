<template>
    <kuu-container :breadcrumbs="breadcrumbs" :urls="urls">
        <v-card-text>
            <v-skeleton-loader v-if="isLoading" type="article"/>
            <v-simple-table v-else-if="item" class="mb-0">
                <tbody>
                <tr>
                    <td class="text-right font-weight-bold" width="25%">ID</td>
                    <td>
                        {{ item[pk] }}
                        <kuu-clipboard class="hover-action" :text="item[pk]"/>
                    </td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Name</td>
                    <td>{{ item.name }}</td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Created</td>
                    <td>{{ item.created_at | formatLocalTime }}</td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Updated</td>
                    <td>{{ item.updated_at | formatLocalTime }}</td>
                </tr>
                </tbody>
            </v-simple-table>
        </v-card-text>

        <v-divider/>
        <v-tabs v-if="item" v-model="tabActiveName">
            <v-tab href="#permission">Permission</v-tab>

            <v-tab-item value="permission">
                <tab-permission :id="localId"/>
            </v-tab-item>
        </v-tabs>
    </kuu-container>
</template>

<script>
    import Model from './model'

    import editor from "./Editor";
    import TabPermission from './Detail/TabPermission'

    export default {
        mixins: [Model],
        data() {
            return {
                editor,
                tabActiveName: 'permission',
            }
        },
        computed: {
            breadcrumbs() {
                return [
                    {text: this.morphs, href: '/organization/roles'},
                    {text: 'Detail'},
                ]
            },
            urls() {
                return {
                    edit: () => {
                        this.showEditor();
                    },
                }
            },
        },
        mounted() {
            this.find();
        },
        components: {
            TabPermission,
        },
    }
</script>
