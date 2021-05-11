import Request from './request'

export default {
    mixins: [Request],
    props: ['id'],
    data() {
        return {
            pk: 'id',
            model: '',
            models: '',
            morph: '',
            morphs: '',

            visible: true,

            item: null,

            input: {},
        }
    },
    watch: {
        model: {
            handler(val) {
                const vm = this;
                vm.models = vm.models || val+'s';
                vm.morph = vm.morph || _.startCase(_.camelCase(val));
                vm.morphs = vm.morphs ||  _.startCase(_.camelCase(vm.models));
            },
            immediate: true,
        }
    },
    computed: {
        $form() {
            return this.$refs.form;
        },
        localId() {
            const vm = this;
            return vm.id || (vm.$route && parseInt(vm.$route.params.id)) || null;
        },
        title() {
            return (this.localId ? 'Edit' : 'Add')+' '+this.morph;
        },
        canList() {
            return this.$canList(this.item);
        },
        canRead() {
            return this.$canRead(this.item);
        },
        canUpdate() {
            return this.$canUpdate(this.item);
        },
        canDelete() {
            return this.$canDelete(this.item);
        },
        canEnable() {
            return this.$canEnable(this.item);
        },
        canDisable() {
            return this.$canDisable(this.item);
        },
    },
    methods: {
        showEditor(id = null) {
            const vm = this;
            const c = vm.mountComponent(vm.editor, {
                propsData: {id: id || vm.localId || undefined},
            });
            c.$on('saved', (data) => {
                vm.doRefreshTable ? vm.doRefreshTable(data) : vm.find();
            });
        },
        find(variablesOrId = null) {
            const vm = this;
            vm.addLoading();
            let variables = {};
            if (_.isObject(variablesOrId)) {
                _.assign(variables, variablesOrId);
            } else if (variablesOrId !== null) {
                variables.id = variablesOrId;
            }
            if (!variables.id) {
                variables.id = vm.localId;
            }
            vm.$query(vm.queries.find, variables, ({ data }) => {
                vm.item = data;
                vm.assignInput(data);
            }).finally(() => vm.doneLoading());
        },
        assignInput(data) {
            this.input = _.assignPick(this.input, data);
        },
        create() {
            const vm = this;
            vm.addHanding();
            vm.$mutate(vm.queries.create, {input: vm.input}, {
                form: vm.$form,
                success({ data }) {
                    vm.onModelSaved(data);
                },
            }).finally(() => vm.doneHanding());
        },
        update() {
            this.updateItem(this.localId, this.input);
        },
        updateItem(itemOrId, fieldsOrValues = null) {
            const vm = this;
            let input = {[vm.pk]: _.isObject(itemOrId) ? itemOrId[vm.pk] : itemOrId};
            if (_.isString(fieldsOrValues)) {
                input[fieldsOrValues] = itemOrId[fieldsOrValues];
            } else if (_.isArray(fieldsOrValues)) {
                fieldsOrValues.forEach(v => {
                    input[v] = itemOrId[v];
                });
            } else if (_.isObject(fieldsOrValues)) {
                _.assign(input, fieldsOrValues);
            }
            vm.addHanding();
            vm.$mutate(vm.queries.update, { input }, {
                form: vm.$form,
                success({ data }) {
                    vm.onModelSaved(data, true);
                },
            }).finally(() => vm.doneHanding());
        },
        del(id = null) {
            const vm = this;
            vm.appConfirmDelete(() => {
                vm.addHanding();
                vm.$mutate(vm.queries.del, {[vm.pk]: id || vm.localId}, ({ data }) => {
                    vm.doRefreshTable ? vm.doRefreshTable() : vm.find();
                }).finally(() => vm.doneHanding());
            });
        },
        onFormSubmit() {
            this.localId ? this.update() : this.create();
        },
        onModelSaved(data, isUpdated = false) {
            const vm = this;
            if (isUpdated) {
                vm.appMessageSuccess(`Update ${vm.morph} Successfully.`);
                vm.$emit('update', data);
            } else {
                vm.appMessageSuccess(`Create ${vm.morph} Successfully.`);
                vm.$emit('create', data);
            }

            vm.visible = false;
            vm.$emit('saved', data);
        },
        assignInputData(all, edit) {
            const vm = this;
            let ret = _.cloneDeep(all);
            if (vm.localId) {
                ret = _.assign({}, ret, edit || {});
            }

            return ret;
        },

        $canAction(action, item) {
            return this.can(this.model, action, item);
        },
        $canList(item) {
            return this.$canAction('list', item);
        },
        $canRead(item) {
            return this.$canAction('read', item);
        },
        $canUpdate(item) {
            return this.$canAction('update', item);
        },
        $canDelete(item) {
            return this.$canAction('delete', item);
        },
        $canEnable(item) {
            return this.$canAction('enable', item);
        },
        $canDisable(item) {
            return this.$canAction('disable', item);
        },
    },
}
