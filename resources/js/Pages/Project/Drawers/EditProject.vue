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
                get: ()=> store.getters['project/id'],
                set: (val)=> store.commit('project/id', val)
            }),
            title: computed({
                get: ()=> store.getters['project/title'],
                set: (val)=> store.commit('project/title', val)
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
            store.dispatch('project/update')
            drawer.value = false
        }
        const destroy = ()=> {
            store.dispatch('project/destroy')
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
