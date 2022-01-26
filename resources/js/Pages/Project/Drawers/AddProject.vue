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
                    <v-text-field label="新規プロジェクト名" v-model="create.title"></v-text-field>
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

        const create = reactive({
            title: "",
            due_date: computed({ get: ()=> store.getters['project/due_date'] }),
        })

        const drawer = computed({
            get: () => store.getters['project/add_drawer'],
            set: () => {
                store.commit('project/add_drawer_op')
                create.title = ""
                store.commit('project/due_date', null)
            }
        })
        const save =() => {
            let title = create.title
            let due_date = create.due_date
            Inertia.post("/project/store", {
                title,
                due_date,
            });
            store.commit('project/add_drawer_op', false)
            console.log({title, due_date})
        }

        return {
            drawer,
            create,
            save,
        }
    },
})
</script>
