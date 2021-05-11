export default {
    data() {
        return {
            search: null,

            searchDebounce: _.debounce(vm => {
                vm.fetchData && vm.fetchData();
            }, 300),
        };
    },
    watch: {
        search() {
            const vm = this;
            vm.$nextTick(() => {
                vm.searchDebounce(vm);
            });
        },
    }
}