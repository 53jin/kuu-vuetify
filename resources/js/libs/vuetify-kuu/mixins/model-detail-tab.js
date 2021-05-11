import Value from './value'

export default {
    mixins: [Value],
    props: {
        loading: Boolean,
    },
    methods: {
        getKey() {
            return (this.localValue || {})[this.pk];
        },
    },
}
