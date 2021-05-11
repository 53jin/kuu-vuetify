import { can } from '@/helpers/permission';

export default {
    computed: {},
    methods: {
        can(modelPermissionName, action, model = null, ownerKey = 'user_id') {
            if (!modelPermissionName || !action) {
                return true;
            }
            const user = this.$root.user;
            let fullAction = modelPermissionName+'-'+action;

            if (can(user, fullAction) || can(user, fullAction+'-all')) {
                return true
            }

            if (!can(user, fullAction+'-own')) {
                return false;
            }

            return model && parseInt(model[ownerKey]) === parseInt(user.id);
        },
    },
}
