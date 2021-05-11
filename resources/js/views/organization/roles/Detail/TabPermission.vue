<template>
    <v-card-text>
        <v-simple-table>
            <thead>
            <tr>
                <th>Module</th>
                <th>Section</th>
                <th>Group</th>
                <th>Permission</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(module, moduleIndex) in permissionsData">
                <template v-for="(section, sectionIndex) in module.values">
                    <template v-for="(group, groupIndex) in section.values">
                        <tr>
                            <td
                                v-if="sectionIndex === 0 && groupIndex === 0"
                                :rowspan="calcModuleRowSpan(module)"
                            >
                                <v-checkbox
                                    hide-details dense class="my-0"
                                    :indeterminate="module._indeterminate && !module._value"
                                    :label="module.name" :value="module._value"
                                    @change="onChangeModule($event, module)"
                                />
                            </td>

                            <td v-if="groupIndex === 0" :rowspan="section.values.length">
                                <v-checkbox
                                    hide-details dense class="my-0"
                                    :indeterminate="section._indeterminate && !section._value"
                                    :label="section.name" :value="section._value"
                                    @change="onChangeSection($event, module, section)"
                                />
                            </td>

                            <td>
                                <v-checkbox
                                    hide-details dense class="my-0"
                                    :indeterminate="group._indeterminate && !group._value"
                                    :label="group.name || '-'" :value="group._value"
                                    @change="onChangeGroup($event, module, section, group)"
                                />
                            </td>

                            <td>
                                <v-layout wrap>
                                    <template v-for="item in group.values">
                                        <v-flex grow shrink mr-2 py-0>
                                            <v-checkbox
                                                v-model="input.permissions" hide-details dense class="my-0"
                                                :value="item.name" :label="item.display_name"
                                            />
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </td>
                        </tr>
                    </template>
                </template>
            </template>
            </tbody>
        </v-simple-table>
        <v-btn color="primary" class="mt-5" @click.prevent="update" :loading="isHandling">Save</v-btn>
    </v-card-text>
</template>

<script>
    import { GetEmployeePermissions } from '@gql/employee.gql'
    import Model from '../model'

    export default {
        mixins: [Model],
        data() {
            return {
                input: {
                    permissions: [],
                },

                permissionsData: null,
            }
        },
        watch: {
            'input.permissions'() {
                this.onChangPermissions();
            },
            item(item) {
                this.input.permissions = _.map(item.permissions, 'name');
            },
        },
        mounted() {
            this.fetchPermissions();
            this.find({withPermissions: true});
        },
        methods: {
            onChangPermissions() {
                const vm = this;
                const permissions = vm.input.permissions;

                if (!vm.permissionsData) {
                    return;
                }
                vm.permissionsData.forEach(module => {
                    let moduleHasChecked = false, moduleAllChecked = true;
                    module.values.forEach(section => {
                        let sectionHasChecked = false, sectionAllChecked = true;
                        section.values.forEach(group => {
                            let groupHasChecked = false, groupAllChecked = true;
                            group.values.forEach(pm => {
                                if (_.includes(permissions, pm.name)) {
                                    groupHasChecked = true;
                                    sectionHasChecked = true;
                                    moduleHasChecked = true;
                                } else {
                                    groupAllChecked = false;
                                    sectionAllChecked = false;
                                    moduleAllChecked = false;
                                }
                            });
                            vm.$set(group, '_indeterminate', groupHasChecked);
                            vm.$set(group, '_value', groupAllChecked);
                        });
                        vm.$set(section, '_indeterminate', sectionHasChecked);
                        vm.$set(section, '_value', sectionAllChecked);
                    });
                    vm.$set(module, '_indeterminate', moduleHasChecked);
                    vm.$set(module, '_value', moduleAllChecked);
                });
            },
            onChangeModule(checked, module) {
                this.$set(module, '_value', checked);
                module.values.forEach(v => {
                    this.onChangeSection(checked, module, v);
                });
            },
            onChangeSection(checked, module, section) {
                this.$set(section, '_value', checked);
                section.values.forEach(v => {
                    this.onChangeGroup(checked, module, section, v);
                });
            },
            onChangeGroup(checked, module, section, group) {
                const vm = this;
                vm.$set(group, '_value', checked);
                group.values.forEach(v => {
                    if (checked) {
                        !_.includes(vm.input.permissions, v.name) && vm.input.permissions.push(v.name);
                    } else {
                        vm.input.permissions = _.remove(vm.input.permissions, n => n !== v.name);
                    }
                });
            },
            calcModuleRowSpan(module) {
                let rowSpan = 0;
                module.values.forEach(v => {
                    rowSpan += v.values.length;
                });
                return rowSpan;
            },
            fetchPermissions() {
                const vm = this;

                vm.$query(GetEmployeePermissions, ({ data }) => {
                    vm.permissionsData = vm.transformPermissionData(data);
                    vm.$nextTick(() => {
                        vm.onChangPermissions();
                    });
                });
            },
            transformPermissionData(data) {
                const vm = this;
                data.forEach(module => {
                    vm.$set(module, '_value', false);
                    vm.$set(module, '_indeterminate', false);
                    module.values.forEach(section => {
                        vm.$set(section, '_value', false);
                        vm.$set(section, '_indeterminate', false);
                        section.values.forEach(group => {
                            vm.$set(group, '_value', false);
                            vm.$set(group, '_indeterminate', false);
                        });
                    });
                });

                return data;
            },
        },
    }
</script>
