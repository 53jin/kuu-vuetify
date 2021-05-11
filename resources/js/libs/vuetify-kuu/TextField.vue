<template>
    <ValidationProvider :name="$attrs.label" :vid="$attrs.name" :rules="rules" v-slot="{ errors, valid }">
        <v-text-field
            v-model="localValue"
            :error-messages="errors"
            :success="valid"
            v-bind="$attrs"
            v-on="$listeners"
        >
            <template #prepend><slot name="prepend"/></template>
            <template #prepend-inner><slot name="prepend-inner"/></template>
            <template #append><slot name="append"/></template>
            <template #append-outer><slot name="append-outer"/></template>
        </v-text-field>
    </ValidationProvider>
</template>

<script>
    import Value from './mixins/value'

    export default {
        mixins: [Value],
        props: {
            rules: {
                type: [Object, String],
                default: ""
            },
        },
    };
</script>
