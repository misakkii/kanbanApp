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
                        プロジェクトの変更
                    </v-list-item-title>
                    <v-text-field label="プロジェクト名" v-model="form.project_name"></v-text-field>
                    <v-text-field readonly label="YYYY-MM-DD" v-model="form.due_date">
                        <template v-slot:append-outer>
                            <date-picker v-model="form.due_date"/>
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
import DatePicker from "@/Components/DatePicker.vue"
import { useStore } from '@/store/index'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    components: {
        DatePicker,
    },
    setup() {
        const store = useStore()

        const form = reactive({
            id : computed({
                get: ()=> store.getters['project/id'],
                set: (val)=> store.commit('project/id', val)
            }),
            project_name: computed({
                get: ()=> store.getters['project/project_name'],
                set: (val)=> store.commit('project/project_name', val)
            }),
            due_date: computed({
                get: ()=> store.getters['project/due_date'],
                set: (val)=> store.commit('project/due_date', val)
            }),
        })

        const drawer = computed({
            get: () => store.getters['project/edit_drawer'],
            set: (val) => store.commit('project/edit_drawer_op', val)
        })

        const update =()=> {
            Inertia.visit('/project/update', {
                method: 'post',
                data: {
                    id: form.id,
                    project_name: form.project_name,
                    due_date: form.due_date,
                }
            }).then(res => {
                drawer.value = false
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
            })
        }
        const destroy = ()=> {
            Inertia.post('/project/destroy', {
                id: form.id
            }).then(res => {
                drawer.value = false
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
            })
        }

        return {
            drawer,
            form,
            update,
            destroy,
        }
    },
})
</script>
