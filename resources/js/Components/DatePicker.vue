<template>
    <v-menu
        v-model="data.menu"
        offset-y
        :close-on-content-click="false">
        <template v-slot:activator="{ on }">
            <v-btn icon color="primary" elevation="0" v-on="on">
                <v-icon>mdi-calendar</v-icon>
            </v-btn>
        </template>
        <v-date-picker v-model="picker" @click="data.menu" />
    </v-menu>
</template>

<script>
import { defineComponent, computed, reactive } from '@vue/composition-api'
import { useStore } from '@/store/index'

export default defineComponent({
    setup() {
        const store = useStore();
        const data = reactive({
            menu: false
        })
        const picker = computed({
            get:() => store.getters['picker'],
            set:(value) => {
                store.commit('picker', value)
                store.commit('task/due_date', value)
                store.commit('project/due_date', value)
                data.menu = false
            }
        });
        return {
            data,
            picker,
        }
    },
})
</script>
