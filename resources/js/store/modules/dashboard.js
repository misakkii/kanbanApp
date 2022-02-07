

const state = {
    edit_drawer: false,

    select_data: {},
    projects: null,
    project_id: null,
    task_id: null,
    title: "",
    due_date: null,

}
const getters = {
    edit_drawer: state => state.edit_drawer,

    user_id: state => state.select_data.pivot.user_id,
    projects: state => state.projects,
    project_id: state => state.project_id,
    task_id: state => state.task_id,
    title: state => state.title,
    due_date: state => state.due_date,
}
const mutations = {
    edit_drawer_op(state, newValue) {
        state.edit_drawer = newValue
    },
    select_data (state, newSelect) {
        state.select_data = newSelect
    },
    projects (state, newProjects) {
        state.projects = newProjects
    },
    project_id (state, newProjectId) {
        state.project_id = newProjectId
    },
    task_id (state, newTaskId) {
        state.task_id = newTaskId
    },
    title (state, newTitle) {
        state.title = newTitle
    },
    due_date (state, newDueDate) {
        state.due_date = newDueDate
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
