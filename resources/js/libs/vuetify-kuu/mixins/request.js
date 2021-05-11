export default {
    data() {
        return {
            loadings: 0,
            handlers: 0,
            requestId: 0,
        }
    },
    computed: {
        isLoading() {
            return this.loadings > 0;
        },
        isHandling() {
            return this.handlers > 0;
        },
    },
    methods: {
        addLoading(d = 1) {
            this.loadings += d;
        },
        addHanding(d = 1) {
            this.handlers += d;
        },
        doneLoading(d = 1) {
            this.loadings -= d;
        },
        doneHanding(d = 1) {
            this.handlers -= d;
        },
        startRequest() {
            return ++this.requestId;
        },
        verifyRequest(requestId) {
            return this.requestId === requestId;
        },
    },
}
