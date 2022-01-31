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
                        プロジェクトの追加
                    </v-list-item-title>
                    <v-text-field label="新規プロジェクト名" v-model="create.project_name"></v-text-field>
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

        const drawer = computed({
            get: () => store.getters['project/add_drawer'],
            set: (val) => {
                store.commit('project/add_drawer_op', val)
                // store.commit('project/title', "")
                store.commit('project/due_date', null)
            }
        })

        const create = reactive({
            project_name: computed({
                get: ()=> store.getters['project/project_name'],
                set: (val)=> store.commit('project/project_name', val) ,
            }),
            due_date: computed({
                get: ()=> store.getters['project/due_date'],
                set: (val)=> store.commit('project/due_date', val),
            }),
        })

        const save = ()=> {
            Inertia.post("/project/store", {
                project_name: create.project_name,
                due_date: create.due_date,
            }).then(res => {
                drawer.value = false
            }).catch(err => {
                const err_msg = err.response.data
                commit('err_msg', err_msg)
            })
        }

        return {
            drawer,
            create,
            save,
        }
    },
})
</script>
