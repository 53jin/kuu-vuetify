<template>
    <span>
        <v-tooltip bottom :value="showTooltip">
            <template #activator="data">
                <span ref="cel">
                    <slot>
                        <v-btn icon small class="ma-0">
                            <v-icon small>fa-copy</v-icon>
                        </v-btn>
                    </slot>
                </span>
            </template>

            <span>Copied!</span>
        </v-tooltip>
    </span>
</template>

<script>
    export default {
        props: {
            text: '',
        },
        data() {
            return {showTooltip: false, disabled: false, clipboard: null, timeoutThread: 0};
        },
        computed: {
            cel() {
                return this.$refs.cel;
            }
        },
        mounted() {
            const vm = this;
            vm.clipboard = new ClipboardJS(vm.cel, {
                text: () => {
                    return vm.text;
                },
            });

            vm.cel.addEventListener('click', () => {
                if (vm.disabled) {
                    return;
                }

                vm.disabled = true;
                vm.showTooltip = true;
                vm.timeoutThread = setTimeout(() => {
                    vm.timeoutThread = 0;
                    vm.disabled = false;
                    vm.showTooltip = false;
                }, 1000);
            });

            vm.cel.addEventListener('mouseover', () => {
                vm.disabled = false;
                vm.showTooltip = false;
                if (vm.timeoutThread) {
                    clearTimeout(vm.timeoutThread);
                }
            });
        },
        destroyed() {
            this.clipboard && this.clipboard.destroy();
        }
    };
</script>