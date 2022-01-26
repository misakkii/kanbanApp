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
                    <v-text-field label="プロジェクト名" v-model="form.title"></v-text-field>
                    <v-text-field label="YYYY-MM-DD" v-model="due_date">
                        <template v-slot:append-outer>
                            <date-picker v-model="due_date"/>
                        </template>
                    </v-text-field>
                    <v-btn color="primary" @click="update">更新</v-btn>

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

        const form = reactive({
            id : computed(()=> store.getters['project/id']),
            title: "",
            due_date: computed(()=> store.getters['project/due_date']),
        })

        const id = computed({
            get: ()=> store.getters['project/id'],
            set: ()=> store.commit('project/id')
        })

        const title = computed({
            get: ()=> store.getters['project/title'],
            set: (value)=> store.commit('project/title', value)
        })

        const due_date = computed({
            get: ()=> store.getters['project/due_date'],
            set: (value)=> store.commit('project/due_date', value)
        })

        const drawer = computed({
            get: () => store.getters['project/edit_drawer'],
            set: () => store.commit('project/edit_drawer_op')
        })

        const update =()=> {
            Inertia.post('/project/update', form);
            form.title = ""
        }

        return {
            drawer,
            id,
            title,
            due_date,
            form,
            update,
        }
    },
})
</script>
