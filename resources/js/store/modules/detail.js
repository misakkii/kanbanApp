const state = {
    show: false,
    dialog_data:{
        executed: {
            label: "開始時間",
            default:'',
            edit: '',
            diff: null,
        },
        suspended: {
            label: "終了時間",
            default:'',
            edit: '',
            diff: null,
        },
    },

}
const getters = {
    show: state => state.show,
    dialog: state => state.dialog_data,
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
