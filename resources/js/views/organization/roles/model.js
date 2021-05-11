import { create, find, list, update } from '@gql/role.gql'

import Model from '@kuu/mixins/model'

export default {
    mixins: [Model],
    data() {
        return {
            model: 'role',
            queries: {
                create, find, list, update,
            },
        }
    },
}
