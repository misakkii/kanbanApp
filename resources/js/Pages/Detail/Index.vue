<template>
    <v-container fluid class="pa-1">
        <v-data-table
            :headers="headers"
            :items="items"
            @click:row="drawer = !drawer"
        ></v-data-table>

        <edit-detail :work_times="data.work_times"/>
    </v-container>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api'
import draggable from 'vuedraggable'
import Layout from '@/Layouts/VuetifyLayout'
import EditDetail from './Drawer/EditDetail.vue';
import { useStore } from '@/store/index'

export default defineComponent({
    layout: Layout,
    components: {
        draggable,
        EditDetail,
    },
    // props: {
    //     work_times: {
    //         type: Array
    //     }
    // },
    setup(props) {
        const store = useStore();

        const data = reactive({
            tasks :['now', 'today', 'waiting'],
            work_times: props.work_times,
            value: null,
            start: null,
            end: null,
        })

        const date = computed({
            get:()=> data.value,
            set:(val)=> {if(data.value !== val) {
                this.$emit('update:value', val)
            }}
        })
        console.log(data.work_times)

        const drawer = computed({
            get: ()=> store.getters['detail/drawer'],
            set: (val)=> store.commit('detail/edit_drawer', val),
        })
        return {
            data,
            date,
            drawer,
            headers:[
                {text: 'タイトル', value: 'title'},
                {text: 'start', value: 'start'},
                {text: 'end', value: 'end'}],
            items:[
                {
                    title: '取材',
                    start: '2022-02-20',
                    end: '2022-02-20',
                }
            ]
        }
    },
})
</script>
