import { can } from './permission'

// import OffersEditorComponent from '../components/console/offers/Editor';

const getRoutes = user => {
    const routes = [], userCan = permissions => {
        return can(user, permissions);
    };
    // Organization
    if (userCan('role-list-*')) {
        routes.push({
            path: '/organization/roles',
            component: require('@views/organization/roles/List').default,
            meta: {keepAlive: true},
        });
    }
    if (userCan('role-read-*')) {
        routes.push({
            path: '/organization/roles/:id(\\d+)',
            component: require('@views/organization/roles/Detail').default,
        });
    }
    if (userCan('employee-list-*')) {
        routes.push({
            path: '/organization/employees',
            component: require('@views/organization/employees/List').default,
            meta: {keepAlive: true},
        });
    }
    if (userCan('employee-read-*')) {
        routes.push({
            path: '/organization/employees/:id(\\d+)',
            component: require('@views/organization/employees/Detail').default,
        });
    }
    // End Organization

    // Settings
    routes.push({
        path: '/account',
        component: require('@views/account/Editor').default,
        meta: {keepAlive: true},
    });

    routes.push({
        path: '*', component: require('@views/NotFound').default, meta: {keepAlive: true},
    });

    return routes;
};

export {getRoutes}
