<template>
    <v-autocomplete
            chips
            small-chips
            clearable
            dense
            v-model="localValue"
            :items="localItems"
            :multiple="multiple"
            :label="localLabel"
            :loading="loading"
            :single-line="singleLine"
            :hide-details="hideDetails"
            :item-text="item => item.related_user.data.name + ' #' + item.related_user_id"
            item-value="related_user_id"
    >
        <template #selection="{ item, index }">
            <template v-if="!collapseTags">
                <v-chip small>
                    {{ item.related_user.data.name }}
                </v-chip>
            </template>
            <template v-else>
                <v-chip small v-if="index === 0">
                    {{ item.related_user.data.name }}
                </v-chip>
                <span v-if="index === 1"
                      class="grey--text caption"
                >(+{{ localValue.length - 1 }} others)</span>
            </template>
        </template>
    </v-autocomplete>
</template>

<script>
    import Select from './mixins/select'

    export default {
        mixins: [Select],
        props:{
            urlParams: Object
        },
        data() {
            return {
                cacheKey: 'employees',

                singularName: 'Employees',
                complexName: 'Employees',
            };
        },
        methods: {
            fetchData() {
                const vm = this;

                vm.loading = true;
                vm.$api.get(`network_employees/all`,{
                    params: vm.urlParams
                })
                    .then((response) => {
                        vm.localItems = vm.$appCache[vm.cacheKey] = response.data.data;
                    }, (error) => {
                        vm.appResponseError(error)
                    })
                    .catch((error) => {
                        vm.appResponseError(error)
                    })
                    .finally(() => {
                        vm.loading = false;
                    });
            },
        },
    };
</script>
