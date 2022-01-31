<template>
    <v-container>
        <template>
                <v-data-table

                    :headers="headers"
                    :items="tasks"
                    @click:row="edit"
                    class="elevation-1"
                    locale="ja-jp"
                    loading-text="読込中"
                    no-data-text="データがありません。"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>タスク完了一覧</v-toolbar-title>

                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </template>
    </v-container>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api'
import Layout from '@/Layouts/VuetifyLayout.vue'
import { useStore } from '@/store/index'

export default defineComponent({
    layout: Layout,
    props: ['projects', 'tasks', 'auth', 'items'],
    setup(props) {
        const store = useStore()

        const data = reactive({
            projects: computed({
                get: ()=> store.getters['task/projects'],
                set: (val)=> store.commit('task/projects', val),
            }),
            created_by: computed({
                get: ()=> store.getters['task/created_by'],
                set: (val)=> store.commit('task/created_by', val),
            }),
        })
        data.projects = props.projects
        data.created_by = props.auth[0].id

        const user = computed({
            get: ()=>store.getters['user/full_name']
        })

        //ドロワーのフラグ
         const add_drawer = computed({
            get: ()=> store.getters['task/add_drawer'],
            set: (val)=> store.commit('task/add_drawer_op', val)
        })

        const edit_drawer = computed({
            get: ()=>store.getters['task/edit_drawer'],
            set: (newValue)=> store.commit('task/edit_drawer_op', newValue)
        })

        const edit = (task)=> {
            edit_drawer.value = !edit_drawer.value
            store.commit('task/id', task.id)
            store.commit('task/project_id', task.project_id)
            store.commit('task/title', task.title)
            store.commit('task/due_date', task.due_date)
            console.log(task)
        }

        return {
            user,
            data,
            edit,
            add_drawer,
            headers: [
                {
                    text: 'プロジェクト名',
                    value: 'project_name',
                },
                {
                    text: 'タスク名',
                    value: 'title',
                },
                {
                    text: '締切日',
                    value: 'due_date',
                },
                {
                    text: 'タスク作成者',
                    value: 'created_by',
                },
            ]
        }
    },
})
</script>
