<template>
    <kuu-container :breadcrumbs="breadcrumbs" :urls="urls">
        <v-card-text>
            <v-skeleton-loader v-if="isLoading" type="article"/>
            <v-simple-table v-else-if="item" class="mb-0">
                <tbody>
                <tr>
                    <td class="text-right font-weight-bold" >User ID</td>
                    <td>
                        {{ item.related_user_id }}
                        <kuu-clipboard class="hover-action" :text="item.related_user_id"/>
                    </td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Status</td>
                    <td>
                        <kuu-status
                            v-model="item.related_user.status" bool-value
                            @input="updateItem(item, {status: $event})"
                            :can-enable="canEnable" :can-disable="canDisable"
                        />
                    </td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">User Name</td>
                    <td>{{ item.related_user.name }}</td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Roles</td>
                    <td>
                        <v-chip-group>
                            <v-chip color="primary" small v-for="role in item.roles" :key="role.id">
                                {{ role.name }}
                            </v-chip>
                        </v-chip-group>
                    </td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Is AM</td>
                    <td>{{ item.is_am }}</td>
                </tr>
                <tr>
                    <td class="text-right font-weight-bold">Is BD</td>
                    <td>{{ item.is_bd }}</td>
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
    </kuu-container>
</template>

<script>
    import Model from './model'

    import editor from './Editor';

    export default {
        mixins: [Model],
        data() {
            return {
                editor
            }
        },
        computed: {
            breadcrumbs() {
                return [
                    {text: 'Employees', href: '/organization/employees'},
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
    }
</script>
