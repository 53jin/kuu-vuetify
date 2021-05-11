<template>
    <v-switch
        v-if="localValue == trueValue && !canDisable"
        v-model="localValue"
        hide-details readonly
        class="mt-0"
        :ripple="false"
        :true-value="trueValue"
        :false-value="falseValue">
    </v-switch>
    <v-switch
        v-else-if="localValue == falseValue && !canEnable"
        v-model="localValue"
        class="mt-0"
        hide-details readonly
        :ripple="false"
        :true-value="trueValue"
        :false-value="falseValue">
    </v-switch>
    <v-switch
        v-else
        v-model="localValue"
        class="mt-0"
        hide-details
        :loading="loading"
        :true-value="trueValue"
        :false-value="falseValue">
    </v-switch>
</template>

<script>
    import Value from './mixins/value'

    export default {
        mixins: [Value],
        props: {
            boolValue: {type: Boolean},
            loading:{ type: Boolean},
            canEnable:{ type: Boolean},
            canDisable:{ type: Boolean},
        },
        computed: {
            trueValue() {
                return this.boolValue ? true : 1;
            },
            falseValue() {
                return this.boolValue ? false : 0;
            },
        },
    };
</script>
