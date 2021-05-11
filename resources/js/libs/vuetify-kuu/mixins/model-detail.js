export default {
    computed: {
        breadcrumbs() {
            return [
                {text: this.morphs, href: '/'+this.models},
                {text: 'Detail'},
            ]
        },
        urls() {
            return {
                edit: () => {
                    this.showEditor();
                },
            };
        },
    },
    mounted() {
        this.find();
    },
}
