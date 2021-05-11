export default {
    props: ['value'],
    data() {
        return {
            localValue: this.value,
        }
    },
    watch: {
        value() {
            this.localValue = this.value;
        },
        localValue() {
            this.$emit('input', this.localValue);
            this.$emit('change', this.localValue);
        }
    }
}