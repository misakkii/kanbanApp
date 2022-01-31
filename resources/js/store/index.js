import Vue from 'vue'
import Vuex from 'vuex'
import { project, task, user } from './modules'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      picker: null,
    },
    getters: {
      picker: state => state.picker
    },
    mutations: {
      picker(state, newValue) {
        state.picker = newValue
      }
    },
    actions: {
    },
    modules: {
      project,
      task,
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
