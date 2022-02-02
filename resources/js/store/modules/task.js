import { Inertia } from '@inertiajs/inertia';

const state = {
    form: {
        id: null,
        project_id: null,
        title: "",
        due_date: null,
    },
    add_drawer: false,
    edit_drawer: false,
    projects: {}
}
const getters = {
    //操作系
    add_drawer: state => state.add_drawer,
    edit_drawer: state => state.edit_drawer,

    //データ系
    id: state => state.form.id,
    projects: state => state.projects,
    project_id: state => state.form.project_id,
    title: state => state.form.title,
    due_date: state => state.form.due_date,
}
const mutations = {
    add_drawer_op(state,newValue) {
        state.add_drawer = newValue
    },
    edit_drawer_op(state,newValue) {
        state.edit_drawer = newValue
    },
    projects(state, newValue) {
        state.projects = newValue
    },
    project_id(state, newValue) {
        state.form.project_id = newValue
    },
    id(state, newValue) {
        state.form.id = newValue
    },
    title(state, newValue) {
        state.form.title = newValue
    },
    due_date(state, newValue) {
        state.form.due_date = newValue
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