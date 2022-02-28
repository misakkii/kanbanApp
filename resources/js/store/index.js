import Vue from 'vue'
import Vuex from 'vuex'
import { project, task, dashboard, detail, user } from './modules'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      picker: null,
      snackbar: false,
    },
    getters: {
      picker: state => state.picker,
      snackbar: state => state.snackbar,
    },
    mutations: {
      picker(state, newValue) {
        state.picker = newValue
      },
      snackbar(state, newValue) { state.snackbar = newValue },
    },
    actions: {
    },
    modules: {
      project,
      task,
      dashboard,
      detail,
      user,
    },
    plugins: [
      createPersistedState({
        key: 'TaskApp',
        storage: window.sessionStorage,
      })
    ],
})

export const useStore = () => store;
export default store;
