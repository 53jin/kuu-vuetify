<template>
    <v-dialog
        v-model="localValue"
        scrollable persistent
        :fullscreen="$vuetify.breakpoint.xsOnly || fullscreen"
        v-bind="binds"
        transition="dialog-bottom-transition"
    >
        <template #activator="props">
            <slot name="activator" v-bind="props"/>
        </template>

        <v-card>
            <v-app-bar flat dark color="primary">
                <v-btn icon dark @click="localValue = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>{{ title }}</v-toolbar-title>
            </v-app-bar>
            <v-progress-linear
                v-if="loading"
                height="6"
                class="my-0"
                indeterminate
            />

            <v-card-text class="py-3">
                <slot/>
            </v-card-text>

            <v-divider/>
            <v-card-actions style="flex:0 1 auto;">
                <v-spacer/>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>

                <v-btn v-if="disabled" disabled>Save</v-btn>
                <v-btn v-else color="blue darken-1" dark @click="$emit('save')" :loading="handing">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import Value from './mixins/value'

    export default {
        mixins: [Value],
        props: {
            loading: Boolean,
            handing: Boolean,
            disabled: Boolean,
            fullscreen: Boolean,
            title: String,
            maxWidth: {default: 600},
        },
        computed: {
            binds() {
                const vm = this;
                let ret = {};

                if (!vm.fullscreen) {
                    ret.maxWidth = vm.maxWidth;
                }

                return ret;
            },
        },
        methods: {
            close() {
                const vm = this;
                vm.localValue = false;
                setTimeout(() => {
                    vm.$destroy();
                }, 3000);
            },
        },
        destroyed() {
            const vm = this;
            vm.$el && vm.$el.remove();
        },
    };
</script>
