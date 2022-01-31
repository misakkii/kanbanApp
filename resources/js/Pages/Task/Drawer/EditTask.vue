<template>
    <v-navigation-drawer
        app
        v-model="drawer"
        right
        temporary
    >
        <v-list>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title>
                        タスクの変更
                    <v-select
                        v-model="edit.project_id"
                        label="プロジェクト名"
                        :items="edit.projects"
                        item-text="project_name"
                        item-value="id"
                    ></v-select>
                    </v-list-item-title>
                    <v-text-field label="プロジェクト名" v-model="edit.title"></v-text-field>
                    <v-text-field readonly label="YYYY-MM-DD" v-model="edit.due_date">
                        <template v-slot:append-outer>
                            <date-picker v-model="edit.due_date"/>
                        </template>
                    </v-text-field>
                    <v-btn color="primary" @click="update">更新</v-btn>
                    <v-btn color="red darken-1" dark @click="destroy">削除</v-btn>

                </v-list-item-content>

            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { defineComponent, computed, reactive } from '@vue/composition-api'
import DatePicker from "@/components/DatePicker.vue"
import { useStore } from '@/store/index'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    components: {
        DatePicker,
    },
    setup() {
        const store = useStore()

        const edit = reactive({
            id : computed({
                get: ()=> store.getters['task/id'],
                set: (val)=> store.commit('task/id', val)
            }),
            projects: computed({
                get: ()=> store.getters['task/projects'],
                set: (val)=> store.commit('task/project_id', val),
            }),
            project_id: computed({
                get: ()=> store.getters['task/project_id'],
                set: (val)=> store.commit('task/project_id', val),
            }),
            title: computed({
                get: ()=> store.getters['task/title'],
                set: (val)=> store.commit('task/title', val)
            }),
            due_date: computed({
                get: ()=> store.getters['task/due_date'],
                set: (val)=> store.commit('task/due_date', val)
            }),
        })

        const drawer = computed({
            get: () => store.getters['task/edit_drawer'],
            set: (val) => store.commit('task/edit_drawer_op', val)
        })

        const update =()=> {
            store.dispatch('task/update')
            drawer.value = false


            // Inertia.visit('/task/update', {
            //     method: 'post',
            //     data: {
            //         id: edit.id,
            //         project_id: edit.project_id,
            //         title: edit.title,
            //         due_date: edit.due_date,
            //     }
            // }).then(res => {
            // }).catch(err => {
            //     const err_msg = err.response.data
            //     commit('err_msg', err_msg)
            // })

        }
        const destroy = ()=> {
            store.dispatch('task/destroy')
            drawer.value = false
        }

        return {
            drawer,
            edit,
            update,
            destroy,
        }
    },
})
</script>
