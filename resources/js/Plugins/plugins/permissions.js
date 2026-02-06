import { usePage } from '@inertiajs/vue3'

/**
 * Check if the current user has a specific permission
 * @param {string} permission - The permission slug to check
 * @returns {boolean}
 */
export function can(permission) {
    const page = usePage()
    const user = page.props.auth?.user
    const permissions = page.props.auth?.permissions || []

    if (!user) return false

    // Super admin has all permissions
    if (page.props.auth?.roles?.includes('super-admin')) {
        return true
    }

    return permissions.includes(permission)
}

/**
 * Check if the current user has any of the given permissions
 * @param {string[]} permissions - Array of permission slugs
 * @returns {boolean}
 */
export function canAny(permissions) {
    return permissions.some(permission => can(permission))
}

/**
 * Check if the current user has all of the given permissions
 * @param {string[]} permissions - Array of permission slugs
 * @returns {boolean}
 */
export function canAll(permissions) {
    return permissions.every(permission => can(permission))
}

/**
 * Check if the current user has a specific role
 * @param {string} role - The role slug to check
 * @returns {boolean}
 */
export function hasRole(role) {
    const page = usePage()
    const roles = page.props.auth?.roles || []
    return roles.includes(role)
}

/**
 * Check if the current user has any of the given roles
 * @param {string[]} roles - Array of role slugs
 * @returns {boolean}
 */
export function hasAnyRole(roles) {
    return roles.some(role => hasRole(role))
}

/**
 * Vue plugin for permissions
 */
export default {
    install(app) {
        // Add global methods
        app.config.globalProperties.$can = can
        app.config.globalProperties.$canAny = canAny
        app.config.globalProperties.$canAll = canAll
        app.config.globalProperties.$hasRole = hasRole
        app.config.globalProperties.$hasAnyRole = hasAnyRole

        // Add v-can directive
        app.directive('can', {
            mounted(el, binding) {
                const permission = binding.value
                if (!can(permission)) {
                    el.style.display = 'none'
                }
            },
            updated(el, binding) {
                const permission = binding.value
                el.style.display = can(permission) ? '' : 'none'
            }
        })

        // Add v-role directive
        app.directive('role', {
            mounted(el, binding) {
                const role = binding.value
                if (!hasRole(role)) {
                    el.style.display = 'none'
                }
            },
            updated(el, binding) {
                const role = binding.value
                el.style.display = hasRole(role) ? '' : 'none'
            }
        })
    }
}
