const state = {
    add_drawer: false,
    edit_drawer: false,
    picker_date: null,
}
const getters = {
    add_drawer: state => state.add_drawer,
    edit_drawer: state => state.edit_drawer,
    picker_date: state => state.picker_date,
}
const mutations = {
    add_drawer_op (state, newValue) {
        state.add_drawer = newValue
    },
    picker_date(state, newValue) {
        state.picker_date = newValue
    },
    edit_drawer_op (state, newValue) {
        state.edit_drawer = newValue
    },

}
const actions = {
    // Inertia
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
