import { login_state } from '../../stores/login_state'

export default function hasRole(allowedRoles = []) {
    const userRoles = login_state.state.userRole || []


    if (!Array.isArray(userRoles)) return false

    return allowedRoles.some(role => userRoles.includes(role))
}
