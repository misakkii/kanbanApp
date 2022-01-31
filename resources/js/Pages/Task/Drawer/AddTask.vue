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
                        タスクの追加
                    </v-list-item-title>
                    <!-- プロジェクトロール -->
                    <v-select
                        v-model="create.project_id"
                        label="プロジェクト名"
                        :items="create.projects"
                        item-text="project_name"
                        item-value="id"
                    ></v-select>
                    <v-text-field label="新規タスク名" v-model="create.title"></v-text-field>
                    <v-text-field readonly label="YYYY-MM-DD" v-model="create.due_date">
                        <template v-slot:append-outer>
                            <date-picker />
                        </template>
                    </v-text-field>
                    <v-btn color="primary" @click="save">保存</v-btn>

                </v-list-item-content>

            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api'
import DatePicker from '@/components/DatePicker.vue'
import { useStore } from '../../../store/index'
import { Inertia } from '@inertiajs/inertia'


export default defineComponent({
    components: {
        DatePicker,
    },
    props: {
        items:{
            type: Array
        }
    },
    setup() {
        const store = useStore()

        const drawer = computed({
            get: () => store.getters['task/add_drawer'],
            set: (val) => {
                store.commit('task/add_drawer_op', val)
                store.commit('task/project_id', null)
                store.commit('task/title', null)
                store.commit('picker', null)
            }
        })

        const user_id = computed(()=> store.getters['user/id'])

        const create = reactive({
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
                set: (val)=> store.commit('task/title', val),
            }),
            due_date: computed({
                get: ()=> store.getters['picker'],
                set: (val)=> store.commit('picker', val)
            }),
            created_by: computed({
                get: ()=> store.getters['task/created_by'],
                set: (val)=> store.commit('task/created_by', val),
            }),
        })

        const save = ()=> {

            Inertia.post('/task/store', {
                project_id: create.project_id,
                title: create.title,
                due_date: create.due_date,
                created_by: create.created_by,
            }).then(res => {
                drawer.value = !drawer.value
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
        })

        }

        return {
            drawer,
            create,
            user_id,
            save,
        }
    },
})
</script>
