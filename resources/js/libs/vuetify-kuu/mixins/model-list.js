import DataTable from "./data-table";

export default {
    mixins: [DataTable],
    computed: {
        breadcrumbs() {
            return [
                {text: this.morphs},
            ]
        },
        urls() {
            return {
                add: () => this.showEditor(),
            };
        },
    },
}
