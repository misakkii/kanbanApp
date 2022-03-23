<template>
<div>
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
                        <v-toolbar-title>タスク一覧</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-btn @click="add_drawer = !add_drawer" color="primary">New Task</v-btn>
                    </v-toolbar>
                </template>
            </v-data-table>
        </template>
    </v-container>
    <add-task
        :auth="auth"
        :users="users"
        :projects="projects"
    />
    <edit-task
        :task_data="data.task"
    ></edit-task>
</div>
</template>

<script>
    import { computed, defineComponent, reactive } from '@vue/composition-api'
    import Layout from '@/Layouts/VuetifyLayout.vue'
    import AddTask from'@/Pages/Task/Drawer/AddTask.vue'
    import ValidateReset from '../functions/ValidateReset'
    import { useStore } from '@/store/index'

    import EditTask from'@/Pages/Task/Drawer/EditTask.vue'


export default defineComponent({
    layout: Layout,
    components: {
        AddTask,
        EditTask,
    },
    props: [
        //一覧データ
        'tasks',
        //登録データ
        'projects',
        'auth',
        'users',
    ],
    setup(props) {
        const store = useStore()
        const {Reset} = ValidateReset()

        //データ系
        const data = reactive({
            projects: computed({
                get: ()=> store.getters['task/projects'],
                set: (val)=> store.commit('task/projects', val),
            }),
            task: 'test data'
        })
        data.projects = props.projects

        const user = computed({
            get: ()=>store.getters['user/full_name']
        })

        //制御系
        const add_drawer = computed({
            get: ()=> store.getters['task/add_drawer'],
            set: (val)=> {
                store.commit('task/add_drawer_op', val)
            }
        })

        const edit_drawer = computed({
            get: ()=>store.getters['task/edit_drawer'],
            set: (newValue)=> store.commit('task/edit_drawer_op', newValue)
        })

        const edit = (task)=> {
            edit_drawer.value = !edit_drawer.value
            Reset()
            store.commit('task/id', task.id)
            store.commit('task/project_id', task.project_id)
            store.commit('task/title', task.title)
            store.commit('task/due_date', task.due_date)
            // console.log(task)
            // axios.post('api/task/update', {})
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
