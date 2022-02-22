const state = {
    drawer: false
}
const getters = {
    drawer: state => state.drawer
}
const mutations = {
    edit_drawer(state, newValue){ state.drawer = newValue },
}
const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
