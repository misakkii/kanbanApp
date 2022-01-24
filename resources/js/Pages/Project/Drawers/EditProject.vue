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
                    <v-text-field label="プロジェクト名"></v-text-field>
                    <v-text-field label="YYYY-MM-DD" v-model="value">
                        <template v-slot:append-outer>
                            <date-picker v-model="value" />
                        </template>
                    </v-text-field>

                </v-list-item-content>

            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { defineComponent, computed } from '@vue/composition-api'
import DatePicker from "@/components/DatePicker.vue"
import { useStore } from '@/store/index'

export default defineComponent({
    components: {
        DatePicker,
    },
    setup() {
        const store = useStore()

        const drawer = computed({
            get: () => store.getters['project/edit_drawer'],
            set: () => store.commit('project/edit_drawer_op')
        })

        return {
            drawer,
            value: null,
        }
    },
})
</script>
