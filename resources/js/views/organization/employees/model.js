import { create, find, list, update, all } from '@gql/employee.gql'

import Model from '@kuu/mixins/model'

export default {
    mixins: [Model],
    data() {
        return {
            model: 'employee',
            queries: {
                create, find, list, update, all
            },
        }
    },
}
