<template>
    <v-autocomplete
            chips
            small-chips
            clearable
            dense
            v-model="localValue"
            :items="hours"
            :multiple="multiple"
            :label="localLabel"
            :loading="loading"
            :single-line="singleLine"
            :hide-details="hideDetails"
    >
        <template #selection="{ item, index }">
            <template v-if="!collapseTags">
                <v-chip small :close="multiple" @click:close="remove(item)">
                    {{ item }}
                </v-chip>
            </template>
            <template v-else>
                <v-chip small v-if="index === 0">
                    {{ item }}
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

        data() {
            return {
                singularName: 'Hour',
                complexName: 'Hours',
            };
        },
        computed: {
            hours() {
                let ret = [];
                for (let i = 0; i < 24; i++) {
                    ret.push(i);
                }

                return ret;
            },
        },
    };
</script>
