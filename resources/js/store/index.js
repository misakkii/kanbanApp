import Vue from 'vue'
import Vuex from 'vuex'
import { project } from './modules'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
    },
    mutations: {
    },
    actions: {
    },
    modules: {
      project
    },
})

export const useStore = () => store;
export default store;
