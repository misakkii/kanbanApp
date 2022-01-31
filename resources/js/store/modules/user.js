const state = {
    id: null,
    last_name: "",
    first_name: "",
}
const getters = {
    id: state => state.id,
    last_name: state => state.last_name,
    first_name: state => state.first_name,
    full_name: state => state.last_name + ' ' + state.first_name
}
const mutations = {
    id(state, newValue) {
        state.id = newValue
    },
    last_name(state, newValue) {
        state.last_name = newValue
    },
    first_name(state, newValue) {
        state.first_name = newValue
    },
}
const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
