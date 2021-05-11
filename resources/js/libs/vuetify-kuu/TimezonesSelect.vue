<template>
    <v-autocomplete
            chips
            small-chips
            :clearable="clearable"
            dense
            v-model="localValue"
            :items="localItems"
            :multiple="multiple"
            :label="localLabel"
            :loading="loading"
            :single-line="singleLine"
            :hide-details="hideDetails"
            item-text="display_name"
            item-value="timezone"
            :disabled="disabled"
            v-bind="$attrs"
    >
        <template #selection="{ item, index }">
            <template v-if="!collapseTags">
                <v-chip small>
                    {{ item.display_name }}
                </v-chip>
            </template>
            <template v-else>
                <v-chip small v-if="index === 0">
                    {{ item.display_name }}
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
        props: {
            clearable: false,
        },
        data() {
            return {
                cacheKey: 'timezones',

                singularName: 'Timezone',
                complexName: 'Timezones',
            };
        },
        methods: {
            fetchData() {
                const vm = this;

                vm.loading = true;
                vm.$api.get(`common/timezone`)
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
