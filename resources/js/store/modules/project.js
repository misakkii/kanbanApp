import { Inertia } from '@inertiajs/inertia'

const state = {
    data: {
        id: null,
        project_name: "",
        due_date: null,
    },
    id: null,
    project_name: "",
    due_date: null,
    completed_at: null,
    deleted_at: null,
    add_drawer: false,
    edit_drawer: false,
    err_msg: {},
}
const getters = {
    // 操作系
    add_drawer: state => state.add_drawer,
    edit_drawer: state => state.edit_drawer,
    // データ系
    data: state => state.data,
    id: state => state.id,
    project_name: state => state.project_name,
    due_date: state => state.due_date,
    completed_at: state => state.completed_at,
    deleted_at: state => state.deleted_at,
}
const mutations = {
    add_drawer_op (state, newValue) {
        state.add_drawer = newValue
    },
    edit_drawer_op (state, newValue) {
        state.edit_drawer = newValue
    },
    data(state, newValue) {
        state.data = newValue
    },
    id(state, newValue) {
        state.id = newValue
    },
    project_name(state, newValue) {
        state.project_name = newValue
    },
    due_date(state, newValue) {
        state.due_date = newValue
    },
    err_msg(state, newValue) {
        state.err_msg = newValue
    }

}
const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
