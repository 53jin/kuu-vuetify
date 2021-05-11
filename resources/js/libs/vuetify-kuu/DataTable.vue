<script>
    import Value from './mixins/value'
    import Request from './mixins/request'

    export default {
        mixins: [Value, Request],
        render(h) {
            const vm = this;
            let slotTop = vm.$slots['top'] || [];
            let slotTopActions = vm.$slots['top.actions'] || [];
            return h('v-card', [
                slotTop.length > 0 ? h('v-card-text', {
                    class: ['pb-0']
                }, slotTop) : null,
                vm.hideTopConsole ? null : h('v-card-title', {
                    class: ['pb-2', slotTop.length > 0 ? 'pt-2' : null],
                },[
                    h('v-btn', {
                        props: {
                            color: 'primary',
                            loading: vm.isLoading,
                            icon: true,
                        },
                        on: {click: vm.fetchData,},
                    }, [
                        h('v-icon', ['refresh']),
                    ]),
                    ...slotTopActions,
                    h('v-spacer'),
                    vm.hideSearch ? null : h('v-text-field', {
                        props: {
                            appendIcon: "search",
                            label: "Search",
                            singleLine: true,
                            clearable: true,
                            hideDetails: true,
                            color: "primary",
                            value: vm.search,
                        },
                        on: {
                            input(val) {
                                vm.search = val;
                                vm.$emit('input.search', val);
                            },
                            keypress(event) {
                                if (event.target !== event.currentTarget) return;
                                if (event.keyCode !== 13) return;
                                event.stopPropagation();
                                event.preventDefault();
                                vm.handleSearch();
                            },
                            'click:clear'() {
                                vm.search = '';
                                vm.handleSearch();
                            },
                            'click:append': vm.handleSearch,
                        }
                    }),
                ]),
                h('v-data-table', {
                    props: vm.localTableProps,
                    scopedSlots: vm.tableScopedSlots,
                    on: {
                        input($event) {
                            vm.localValue = $event;
                        },
                        'update:options'($event) {
                            vm.options = $event;
                        },
                    }
                }),
            ]);
        },
        props: {
            apiPath: String,
            apiParams: Object,
            query: {type: Object, default: null},
            variables: Object,
            dataKey: {type: String, default: 'data'},
            noPagination: Boolean,
            hideTopConsole: Boolean,
            hideSearch: Boolean,
            disableAutoFetchData: Boolean,
            pageSizes: {
                type: Array,
                default: () => [10, 15, 25, 50, 100],
            },
            sortBy: String | Boolean | Array,
            sortDesc: String | Boolean | Array,

            tableProps: {
                type: Object,
                default: () => {},
            },
        },
        data() {
            return {
                expand: false,

                currentPage: 1,
                pageSize: 15,
                perPage: 0,
                total: 0,

                search: null,

                options: {},
                data: null,
                items: [],
            };
        },
        computed: {
            localTableProps() {
                const vm = this;
                let ret = _.cloneDeep(vm.tableProps);
                if (!vm.noPagination) {
                    ret.serverItemsLength = vm.total;
                    ret.itemsPerPage = vm.pageSize;
                    ret.page = vm.currentPage;
                }
                ret.items = vm.items;
                ret.calculateWidths = true;
                ret.options = vm.options;
                if (!ret.footerProps) {
                    ret.footerProps = {};
                }
                if (!vm.noPagination) {
                    ret.footerProps.itemsPerPageOptions = vm.pageSizes;
                } else {
                    ret.footerProps.itemsPerPageOptions = [-1];
                }
                if (ret.mustSort === undefined) {
                    ret.mustSort = true;
                }
                if (!ret.sortBy) {
                    ret.sortBy = vm.sortBy;
                    ret.sortDesc = vm.sortDesc;
                }
                ret.value = vm.localValue;
                ret.loading = vm.isLoading;
                ret.mobileBreakpoint = 0;

                return ret;
            },
            tableScopedSlots() {
                const vm = this;
                const ret = _.cloneDeep(vm.$scopedSlots);
                _.each(['top', 'top.actions'], key => {
                    ret[key] && delete ret[key];
                });
                console.log(ret, 'ret');

                return ret;
            },
        },
        mounted() {
            const vm = this;
            !vm.disableAutoFetchData && vm.fetchData();
            console.log('vm.$scopedSlots', vm.$scopedSlots);
        },
        watch: {
            options: {
                handler(options) {
                    const vm = this;
                    if (!vm.noPagination) {
                        vm.currentPage = options.page;
                        vm.pageSize = options.itemsPerPage;
                        vm.fetchData();
                    }
                    if (!_.isEqual(options.sortBy, vm.sortBy)) {
                        vm.$emit('update:sortBy', options.sortBy);
                    }
                    if (!_.isEqual(options.sortDesc, vm.sortBy)) {
                        vm.$emit('update:sortDesc', options.sortDesc);
                    }
                },
                deep: true,
            },
        },
        methods: {
            fetchData() {
                const vm = this;
                if (vm.isLoading) {
                    return;
                }

                vm.addLoading();
                const requestId = vm.startRequest();

                let params = {};
                let query = {};
                if (vm.apiParams) {
                    params = Object.assign({}, vm.apiParams);
                    query.params = vm.apiParams;
                }

                if (!vm.noPagination) {
                    params.page = vm.currentPage;
                    params.limit = vm.pageSize;

                    query.page = vm.currentPage;
                    query.limit = vm.pageSize;
                }
                if (vm.options.sortBy && vm.options.sortBy.length > 0 && vm.options.sortBy.length === vm.options.sortDesc.length) {
                    let sort = [];
                    if (vm.options.sortBy.length > 1) {
                        let orderBy = [];
                        vm.options.sortBy.map((column, index) => {
                            orderBy.push(column+','+(vm.options.sortDesc[index] ? 'desc' : 'asc'));

                            sort.push(column+':'+(vm.options.sortDesc[index] ? 'desc' : 'asc'))
                        });
                        params.orderBy = orderBy.join(';');
                    } else {
                        params.orderBy = vm.options.sortBy[0];
                        params.sortedBy = vm.options.sortDesc[0] ? 'desc' : 'asc';

                        sort.push(vm.options.sortBy[0]+':'+(vm.options.sortDesc[0] ? 'desc' : 'asc'))
                    }
                    query.sort = sort;
                }
                if (vm.search) {
                    params.search = vm.search;
                    query.q = vm.search;
                }

                console.table({
                    apiPath: vm.apiPath,
                    params,
                });

                vm.$emit('before-request');

                if (vm.query && vm.$query) {
                    vm.$query(vm.query, { query }, (data) => {
                        if (!vm.verifyRequest(requestId)) {
                            return;
                        }

                        data = data[vm.dataKey];
                        if (!data || _.isArray(data)) {
                            vm.items = data;
                        } else {
                            vm.items = data.data;
                            vm.currentPage = data.current_page;
                            vm.perPage = data.per_page;
                            vm.total = data.total;
                        }
                        vm.data = data;
                    }).finally(() => {
                        vm.doneLoading();
                        vm.$emit('response', vm.data);
                    });
                } else if (vm.$api) {
                    vm.$api.get(vm.apiPath, { params }).then((response) => {
                        if (!vm.verifyRequest(requestId)) {
                            return;
                        }

                        const data = response.data;
                        vm.items = data.data;
                        if (data.meta && data.meta.pagination) {
                            const pagination = data.meta.pagination;
                            vm.currentPage = pagination.current_page;
                            vm.perPage = pagination.per_page;
                            vm.total = pagination.total;
                        }
                        vm.data = data;
                    }, vm.apiError)
                        .catch(vm.apiError)
                        .finally(() => {
                            vm.doneLoading();
                            vm.$emit('response', vm.data);
                        });
                }
            },
            apiError(error) {
                this.$emit('responseError', error);

                this.appResponseError(error)
            },
            handleSearch() {
                this.$nextTick(() => {
                    this.currentPage = 1;
                    this.fetchData();
                });
            },
        },
    };
</script>
