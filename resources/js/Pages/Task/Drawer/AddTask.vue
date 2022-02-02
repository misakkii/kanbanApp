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
                        v-model="data.projects"
                        label="プロジェクト名"
                        :items="data.projects"
                        item-text="project_name"
                        item-value="id"
                    ></v-select>
                    <v-text-field label="新規タスク名" v-model="form.title"></v-text-field>
                    <v-text-field readonly label="YYYY-MM-DD" v-model="form.due_date">
                        <template v-slot:append-outer>
                            <date-picker />
                        </template>
                    </v-text-field>
                    <v-select
                        label="参加者"
                        v-model="form.assign"
                        :items="data.users"
                        item-text="last_name"
                        item-value="id"
                        multiple
                        chips
                        clearable
                    >
                    </v-select>
                    <v-btn color="primary" @click="save">保存</v-btn>

                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api'
import DatePicker from '@/components/DatePicker.vue'
import { useStore } from '@/store/index'
import { Inertia } from '@inertiajs/inertia'


export default defineComponent({
    components: {
        DatePicker,
    },
    props: {
        auth:{
            type: Array
        },
        users:{
            type: Array
        },
        projects:{
            type: Array
        },
    },
    setup(props) {
        const store = useStore()

        // const select = reactive(props.auth)

        const drawer = computed({
            get: () => store.getters['task/add_drawer'],
            set: (val) => {
                store.commit('task/add_drawer_op', val)
                form.title = ""
                data.users = props.users
                store.commit('picker', null)
            }
        })

        const data = reactive({
            projects: computed({
                get: ()=> props.projects,
                set: (val)=> form.project_id = val
            }),
            users: props.users,
        })

        const form = reactive({
            project_id: null,
            title: "",
            due_date: computed({
                get: ()=> store.getters['picker'],
                set: (val)=> store.commit('picker', val)
            }),
            assign: [props.auth[0].id],
            created_by: props.auth[0].id
        })

        const save = ()=> {
            Inertia.post('/task/store', {
                user_id: form.assign,
                project_id: form.project_id,
                title: form.title,
                due_date: form.due_date,
                created_by: form.created_by,
            }).then(res => {
                drawer.value = !drawer.value
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
        })

        }

        return {
            drawer,
            data,
            form,
            save,
        }
    },
})
</script>
