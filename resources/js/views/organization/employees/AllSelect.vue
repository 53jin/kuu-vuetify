<template>
    <v-autocomplete
        v-model="localValue"
        :loading="isLoading"
        :items="items"
        :label="$attrs.label || morph"
        item-value="related_user.id"
        :item-text="item => itemText(item)"
        v-bind="$attrs"
    >
        <template #item="{ item }">
                {{ itemText(item) }}
        </template>
        <template #selection="{ item }">
                {{ itemText(item) }}
        </template>
    </v-autocomplete>
</template>

<script>
    import Value from '@kuu/mixins/value'
    import Model from './model';

    export default {
        mixins: [Value, Model],
        data() {
            return {
                items: [],
            }
        },
        mounted() {
            const vm = this;
            vm.$query(vm.queries.all, ({ data }) => {
                vm.items = data;
            });
        },
        methods: {
            itemText(item) {
                if(!item.related_user){
                    return ;
                }
                return `#${item.related_user.id} ${item.related_user.name}`;
            }
        },
    }
</script>
