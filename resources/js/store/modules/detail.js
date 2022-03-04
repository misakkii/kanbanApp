const state = {
    show: false
}
const getters = {
    show: state => state.show
}
const mutations = {
    show(state, newValue){ state.show = newValue },
}
const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
