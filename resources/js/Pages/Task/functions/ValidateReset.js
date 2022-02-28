import { useStore } from '@/store/index'

export default function() {
    const store = useStore()
    const Reset = ()=> {
        store.commit('task/vd_project_id_err', false)
        store.commit('task/vd_project_id_msg', [])
        store.commit('task/vd_title_err', false)
        store.commit('task/vd_title_msg', [])
        store.commit('task/vd_due_date_err', false)
        store.commit('task/vd_due_date_msg', [])
    }

    return {
        Reset,
    }
}
