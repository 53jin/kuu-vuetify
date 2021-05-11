import Value from './value'

export default {
    mixins: [Value],
    props: {
        label: {},
        items: {},
        multiple: Boolean,
        hideDetails: Boolean,
        singleLine: Boolean,
        collapseTags: Boolean,
        disabled: Boolean,
        itemValue: {default: ''},
    },
    data() {
        return {
            loading: false,
            pk: 'id',

            localItems: this.items ? _.cloneDeep(this.items) : [],
        };
    },
    computed: {
        localLabel() {
            const vm = this;
            if (vm.label) {
                return vm.label;
            }

            return vm.multiple ? vm.complexName : vm.singularName;
        },
    },
    mounted() {
        const vm = this;
        if (vm.items) {
            return;
        }

        if (!vm.$appCache[vm.cacheKey]) {
            vm.fetchData && vm.fetchData();
        } else {
            vm.localItems = vm.$appCache[vm.cacheKey];
        }
    },
    methods: {
        hasItem(item) {
            const vm = this;
            if (!vm.localValue) {
                return false;
            }

            return vm.localValue.indexOf(item) > -1;
        },
        remove(item) {
            const vm = this;
            if (!vm.localValue) {
                return;
            }

            vm.localValue.splice(vm.localValue.indexOf(item[vm.itemValue || vm.pk]), 1);
            vm.localValue = [...vm.localValue];
        },
    },
    watch: {
        items() {
            this.localItems = this.items;
        },
    }
}
