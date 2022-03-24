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
                    <v-list-item-title>タスクの変更</v-list-item-title>
                    <v-select
                        v-model="edit.project_id"
                        label="プロジェクト名"
                        :items="edit.projects"
                        item-text="project_name"
                        item-value="id"
                        :error="validate.project_id.error"
                        :error-messages="validate.project_id.message"
                    ></v-select>

                    <v-text-field
                        label="タスク名"
                        v-model="edit.title"
                        :error="validate.title.error"
                        :error-messages="validate.title.message"
                    ></v-text-field>
                    <v-text-field
                        readonly
                        label="YYYY-MM-DD"
                        v-model="edit.due_date"
                        :error="validate.due_date.error"
                        :error-messages="validate.due_date.message"
                    >
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
import { defineComponent, computed, reactive, onMounted } from '@vue/composition-api'
import DatePicker from "@/Components/DatePicker.vue"
import { useStore } from '@/store/index'
import { Inertia } from '@inertiajs/inertia'
import ValidateReset from '../functions/ValidateReset'

export default defineComponent({
    components: {
        DatePicker,
    },
    props: {
        task_data: {
            type: String
        }
    },
    setup() {
        const store = useStore()
        const {Reset} = ValidateReset()

        const edit = reactive({
            id: computed({
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

        const validate = reactive({
            project_id: {
                error: computed({
                    get: ()=> store.getters['task/vd_project_id_err'],
                    set: (val)=> store.commit('task/vd_project_id_err', val)
                }),
                message: computed({
                    get: ()=> store.getters['task/vd_project_id_msg'],
                    set: (val)=> store.commit('task/vd_project_id_msg', val)
                }),
            },
            title: {
                error: computed({
                    get: ()=> store.getters['task/vd_title_err'],
                    set: (val)=> store.commit('task/vd_title_err', val)
                }),
                message: computed({
                    get: ()=> store.getters['task/vd_title_msg'],
                    set: (val)=> store.commit('task/vd_title_msg', val)
                }),
            },
            due_date: {
                error: computed({
                    get: ()=> store.getters['task/vd_due_date_err'],
                    set: (val)=> store.commit('task/vd_due_date_err', val)
                }),
                message: computed({
                    get: ()=> store.getters['task/vd_due_date_msg'],
                    set: (val)=> store.commit('task/vd_due_date_msg', val)
                }),
            },
        })
        onMounted(()=> {
            Reset()
        })

        const drawer = computed({
            get: () => store.getters['task/edit_drawer'],
            set: (val) => store.commit('task/edit_drawer_op', val)
        })

        const update =()=> {
            // Inertia.visit('/task/update', {
            //     method: 'post',
            //     data: {
            //         id: edit.id,
            //         project_id: edit.project_id,
            //         title: edit.title,
            //         due_date: edit.due_date,
            //     }
            // }).then(res => {
            //     drawer.value = false
            // }).catch(err => {
            //     const err_msg = err.response.data
            //     commit('err_msg', err_msg)
            // })

            axios.post('/task/update', {
                id: edit.id,
                project_id: edit.project_id,
                title: edit.title,
                due_date: edit.due_date,
            }).then(res => {
                console.log(res);
                // drawer.value = false
                store.commit('snackbar', true);
            }).catch(err => {
                console.log(err.response.data);
                if(err.response.data[0].project_id) {
                    validate.project_id.error = true
                    validate.project_id.message = err.response.data[0].project_id
                }
                if(err.response.data[1].title) {
                    validate.title.error = true
                    validate.title.message = err.response.data[1].title
                }
                if(err.response.data[2].due_date) {
                    validate.due_date.error = true
                    validate.due_date.message = err.response.data[2].due_date
                }
            })
        }

        const destroy = ()=> {
            Inertia.post('/task/destroy', {
                id: edit.id
            }).then(res => {
                drawer.value = false
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
            })
        }

        return {
            drawer,
            edit,
            validate,
            update,
            destroy,
        }
    },
})
</script>
