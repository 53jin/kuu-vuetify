const hasPermission = (user, permission) => {
    if (!user || user.isPublisher) {
        return false;
    }

    if (user.is_owner) {
        return true;
    }

    if (!permission || !user.employee || !user.employee.permissions) {
        return false;
    }

    return user.employee.permissions.some(pm => {
        return new RegExp("^" + permission.replace(/\-/g, '\\-').split("*").join(".*") + "$").test(pm);
    });
};

const can = (user, permissions) => {
    if (typeof permissions === 'string') {
        return hasPermission(user, permissions);
    }

    if (typeof permissions.forEach === 'function') {
        permissions.forEach(permission => {
            if (hasPermission(user, permission)) {
                return true;
            }
        });
    }

    return false;
};

export {
    hasPermission,
    can,
}
