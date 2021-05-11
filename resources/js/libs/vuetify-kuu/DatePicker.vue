<template>
    <div>
        <vue-date-picker
            v-model="localValue"
            fullscreen-mobile
            :range="range"
            :range-presets="localRangePresets"
            :min-date="minDate"
            :max-date="maxDate"
            :disabled="disabled"
            format="YYYY-MM-DD"
            format-header="YYYY-MM-DD"
            placeholder="Select Date"
            v-bind="$attrs"
        />
    </div>
</template>

<script>
    import Value from './mixins/value'

    export default {
        mixins: [Value],
        props: {
            range: Boolean,
            rangePresets: Array,
            minDate: Array,
            disabled: Boolean,
            maxDate: {default: () => dayjs().add(1, 'day').toDate()},
        },
        data() {
            return {
            }
        },
        computed: {
            localRangePresets() {
                const vm = this;
                let ret = [];
                if (vm.range && (!vm.rangePresets || vm.rangePresets.length === 0)) {
                    let td = dayjs(),
                        ytd = td.clone().add(-1, 'day');
                    ret = [
                        {
                            name: 'Today', dates:{
                                start: td,
                                end: td,
                            }
                        },
                        {
                            name: 'YTD', dates:{
                                start: ytd,
                                end: ytd,
                            }
                        },
                        {
                            name: '3D', dates:{
                                start: td.clone().add(-2, 'day'),
                                end: td,
                            }
                        },
                        {
                            name: '7D', dates:{
                                start: td.clone().add(-6, 'day'),
                                end: td,
                            }
                        },
                        {
                            name: '30D', dates:{
                                start: td.clone().add(-29, 'day'),
                                end: td,
                            }
                        },
                        {
                            name: 'This Month', dates:{
                                start: td.clone().startOf('month'),
                                end: td,
                            }
                        },
                    ];
                }else if(vm.rangePresets || vm.rangePresets.length > 0){
                    ret = vm.rangePresets;
                }

                return ret;
            },
        },
        watch: {
        },
        created() {
        },
    };
</script>
