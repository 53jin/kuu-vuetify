<template>
    <ValidationProvider :name="$attrs.label" :vid="$attrs.name" :rules="rules" v-slot="{ errors, valid }">
        <v-textarea
            v-model="localValue"
            :error-messages="errors"
            :success="valid"
            v-bind="$attrs"
        />
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
