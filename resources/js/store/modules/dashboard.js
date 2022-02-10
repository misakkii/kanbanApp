

const state = {
    edit_drawer: false,
    snackbar: false,
    snackbar_message: "",

    get: {
        users_tasks: [],
    },
    select_data: {},
    now_task_count: null,
    standbys: {},
    projects: null,
    form: {
        project_id: null,
        title: "",
        due_date: null,
    },
}
const getters = {
    edit_drawer: state => state.edit_drawer,
    snackbar: state => state.snackbar,
    snackbar_message: state => state.snackbar_message,
    users_tasks: state => state.get.users_tasks,

    projects: state => state.projects,
    standbys: state => state.standbys,

    //セレクト時のデータ
    task_id: state => state.select_data.id,
    user_id: state => state.select_data.pivot.user_id,
    now_task_count: state => state.now_task_count,
    //form
    project_id: state => state.form.project_id,
    title: state => state.form.title,
    due_date: state => state.form.due_date,
}
const mutations = {
    edit_drawer_op(state, newValue) {
        state.edit_drawer = newValue
    },
    snackbar(state, newValue) { state.snackbar = newValue },
    snackbar_message(state, newMessage) { state.snackbar_message = newMessage },

    users_tasks (state, newData) {
        state.get.users_tasks = newData
    },
    select_data (state, newSelect) {
        state.select_data = newSelect
    },
    now_task_count (state, newCount) {
        state.now_task_count = newCount
    },

    standbys (state, newStandbys) {
        state.standbys = newStandbys
    },
    projects (state, newProjects) {
        state.projects = newProjects
    },
    project_id (state, newProject_id) {
        state.form.project_id = newProject_id
    },
    title (state, newTitle) {
        state.form.title = newTitle
    },
    due_date (state, newDue_date) {
        state.form.due_date = newDue_date
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
