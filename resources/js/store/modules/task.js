import { Inertia } from '@inertiajs/inertia';

const state = {
    form: {
        id: null,
        project_id: null,
        title: "",
        due_date: null,
        created_by: null,
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
    created_by: state => state.form.created_by,
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
    created_by(state, newValue) {
        state.form.created_by = newValue
    },
}
const actions = {
    store({ commit }) {
        Inertia.post('/task/store', {
            project_id: state.form.project_id,
            title: state.form.title,
            due_date: state.form.due_date,
            created_by: state.form.created_by,
        }).then(res => {
        }).catch(err => {
            const err_msg = err.response.data
            commit('err_msg', err_msg)
        })
    },
    update({ commit }) {
        Inertia.visit('/task/update', {
            method: 'post',
            data: {
                id: state.form.id,
                project_id: state.form.project_id,
                title: state.form.title,
                due_date: state.form.due_date,
            }
        }).then(res => {
        }).catch(err => {
            const err_msg = err.response.data
            commit('err_msg', err_msg)
        })
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
