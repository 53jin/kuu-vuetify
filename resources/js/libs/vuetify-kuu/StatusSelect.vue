
<template>
    <v-autocomplete
        v-model="localValue"
        :items="options"
        clearable dense hide-details
        label="Status"
    />
</template>

<script>
    import Value from './mixins/value'

    export default {
        mixins: [Value],
        data() {
            return {
                options: [
                    {text: 'Active', value: 1},
                    {text: 'Disabled', value: 0}
                ],
            }
        },
    };
</script>
