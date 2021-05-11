export default {
    computed: {
        $table() {
            return this.$refs.table;
        },
    },
    methods: {
        doRefreshTable(data = null) {
            this.$nextTick(() => {
                this.$table.fetchData();
            });
        },
    },
}
