<template>
    <v-container>
        <template>
                <v-data-table

                    :headers="headers"
                    :items="items"
                    @click:row="edit"
                    class="elevation-1"
                    locale="ja-jp"
                    loading-text="読込中"
                    no-data-text="データがありません。"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>プロジェクト一覧</v-toolbar-title>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </template>
    </v-container>
</template>

<script>
import { defineComponent, computed } from '@vue/composition-api'
import Layout from '@/Layouts/VuetifyLayout.vue'
import { useStore } from '@/store/index'

export default defineComponent({
    layout: Layout,
    props: {
        items: {
            type: Array,
        }
    },
    setup() {
        const store = useStore()

        const add_drawer = computed({
            get: ()=> store.getters['project/add_drawer'],
            set: (newValue)=> store.commit('project/add_drawer_op', newValue)
        })
        const edit_drawer = computed({
            get: ()=>store.getters['project/add_drawer'],
            set: (newValue)=> store.commit('project/edit_drawer_op', newValue)
        })

        const edit =(item)=>{
            edit_drawer.value = !edit_drawer.value
            store.commit('project/id', item.id)
            store.commit('project/project_name', item.project_name)
            store.commit('project/due_date', item.due_date)
            console.log(item)
        }
        return {
            add_drawer,
            edit_drawer,
            edit,
            headers: [
                {
                    text: 'プロジェクト名',
                    value: 'project_name'
                },
                {
                    text: '締め切り期日',
                    value: 'due_date'
                },
            ],
        }
    },
})
</script>
