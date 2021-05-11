function install (Vue) {
    Vue.component('kuu-container', require('./Container').default);
    Vue.component('kuu-clipboard', require('./Clipboard').default);
    Vue.component('kuu-m', require('./M').default);
    Vue.component('kuu-form', require('./Form').default);
    Vue.component('kuu-text-field', require('./TextField').default);
    Vue.component('kuu-textarea', require('./Textarea').default);
    Vue.component('kuu-upload', require('./Upload').default);
    Vue.component('kuu-editor-dialog', require('./EditorDialog').default);
    Vue.component('kuu-status', require('./Status').default);
    Vue.component('kuu-status-select', require('./StatusSelect').default);

    Vue.component('kuu-data-table', require('./DataTable').default);

    Vue.component('kuu-timezones-select', require('./TimezonesSelect').default);
    Vue.component('kuu-date-picker', require('./DatePicker').default);
    Vue.component('kuu-hours-select', require('./HoursSelect').default);
    Vue.component('kuu-employees-select', require('./EmployeesSelect').default);
    Vue.component('kuu-picker-week-time', require('./PickerWeekTime').default);

}

const Plugin = {
    install
};

/* istanbul ignore next */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Plugin)
}

export default Plugin
