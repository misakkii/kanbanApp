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
                        v-model="form.project_id"
                        label="プロジェクト名"
                        :items="form.projects"
                        item-text="project_name"
                        item-value="id"
                    ></v-select>
                    </v-list-item-title>
                    <v-text-field label="プロジェクト名" v-model="form.title"></v-text-field>
                    <v-text-field readonly label="YYYY-MM-DD" v-model="form.due_date">
                        <template v-slot:append-outer>
                            <date-picker v-model="form.due_date"/>
                        </template>
                    </v-text-field>
                    <v-btn color="primary" @click="update">更新</v-btn>
                    <!-- <v-btn color="red darken-1" dark @click="destroy">削除</v-btn> -->
                    <v-btn color="yellow" @click="assignOff">アサインの解除</v-btn>
                    <v-btn color="purple lighten-1" @click="start" dark >タスクを実行</v-btn>
                    <v-btn color="purple darken-1" @click="back" dark >タスクを待機</v-btn>
                    <v-btn color="purple darken-2" @click="complete" dark >タスクを完了</v-btn>
                    <v-btn color="purple" @click="onClick" dark >snackbar表示</v-btn>
                    <v-btn color="purple" @click="onTest" dark >test</v-btn>
                    {{now_count}}

                </v-list-item-content>

            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { defineComponent, computed, reactive } from '@vue/composition-api'
import DatePicker from "@/Components/DatePicker.vue"
import { useStore } from '@/store/index'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    components: {
        DatePicker,
    },
    setup(props) {
        const store = useStore()

        const onClick =()=> {
            snackbar.value = true
        }

        const drawer = computed({
            get: () => store.getters['dashboard/edit_drawer'],
            set: (val) => store.commit('dashboard/edit_drawer_op', val)
        })

        const snackbar = computed({
            get: ()=> store.getters['dashboard/snackbar'],
            set: (val)=> store.commit('dashboard/snackbar', val),
        })

        const form = reactive({
            user_id: computed(()=> store.getters['dashboard/user_id']),
            task_id : computed(()=> store.getters['dashboard/task_id']),
            projects: computed(()=> store.getters['dashboard/projects']),
            project_id: computed({
                get: () => store.getters['dashboard/project_id'],
                set: (val) => store.commit('dashboard/project_id', val)
            }),
            title: computed({
                get: () => store.getters['dashboard/title'],
                set: (val) => store.commit('dashboard/title', val)
            }),
            due_date: computed({
                get: ()=> store.getters['dashboard/due_date'],
                set: (val)=> store.commit('picker', val)
            }),
        })

        const update =()=> {
            Inertia.visit('/dashboard/update', {
                method: 'post',
                data: {
                    id: form.task_id,
                    project_id: form.project_id,
                    title: form.title,
                    due_date: form.due_date,
                }
            }).then(res => {
                store.commit('dashboard/edit_drawer_op', false)
            }).catch(err => {
                const err_msg = err.response.data
                console.log(err_msg)
                commit('err_msg', err_msg)
            })
        }

        const assignOff =()=> {
            Inertia.visit('assign/off', {
                method: 'post',
                data: {
                    task_id: form.task_id,
                    user_id: form.user_id,
                }
            })
        }
        const now_count = computed({
            get: ()=> store.getters['dashboard/now_task_count']
        })

        //status = now, executed_time
        const start =()=> {
            // console.log(now_count.value)
            if(now_count.value >= 1) {
                let text = "実行できるタスクは1件までです"
                store.commit('dashboard/snackbar_message', text)
                snackbar.value = true
            } else {
                Inertia.visit('task/execute', {
                    method : 'post',
                    data: {
                        task_id: form.task_id,
                        user_id: form.user_id,
                    }
                })
                drawer.value = !drawer.value
                // console.log("実行中のタスクはありません")
            }
        }

        //status = today, suspended_time
        const back =()=> {
            Inertia.visit('task/suspend', {
                method: 'post',
                data: {
                    task_id: form.task_id,
                    user_id: form.user_id,
                }
            })
            drawer.value = !drawer.value
        }

        //status = done, completed_time
        const complete =()=> {
            Inertia.visit('task/complete', {
                method: 'post',
                data: {
                    task_id: form.task_id,
                    user_id: form.user_id,
                }
            })
            drawer.value = !drawer.value
        }

        const destroy = ()=> {
            Inertia.post('/task/destroy', {
                id: form.task_id
            }).then(res => {
                drawer.value = false
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
            })
        }

        const onTest =()=> {
            Inertia.reload({ only: ['users_tasks'] })
        }

        return {
            drawer,
            form,
            now_count,
            update,
            assignOff,
            start,
            back,
            complete,
            destroy,
            onClick,
            onTest,
        }
    },
})
</script>
