import { Inertia } from '@inertiajs/inertia';

const state = {
    form: {
        id: null,
        project_id: null,
        title: "",
        due_date: null,
    },
    validate: {
        project_id: {
            error: false,
            message: [],
        },
        title: {
            error: false,
            message: [],
        },
        due_date: {
            error:false,
            message: []
        },
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
    vd_project_id_err: state => state.validate.project_id.error,
    vd_project_id_msg: state => state.validate.project_id.message,
    vd_title_err: state => state.validate.title.error,
    vd_title_msg: state => state.validate.title.message,
    vd_due_date_err: state => state.validate.due_date.error,
    vd_due_date_msg: state => state.validate.due_date.message,
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
    vd_project_id_err(state, val) {
        state.validate.project_id.error = val
    },
    vd_project_id_msg(state, val) {
        state.validate.project_id.message = val
    },
    vd_title_err(state, val) {
        state.validate.title.error = val
    },
    vd_title_msg(state, val) {
        state.validate.title.message = val
    },
    vd_due_date_err(state, val) {
        state.validate.due_date.error = val
    },
    vd_due_date_msg(state, val) {
        state.validate.due_date.message = val
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
