<template>
    <v-container fluid class="pa-1">
        <v-row>
            <!-- <v-col cols="12" md="3" v-for="(status, index) in data.statuses" :key="index"> -->
            <v-col
                v-for="(status, index) in data.statuses" :key="index"
                cols="12"
                md="3"
            >
                <v-card>
                    <v-card-title>{{index}}</v-card-title>

                    <v-sheet min-height="96">
                        <v-col cols="12">
                            <task-card
                                v-for="(card, index) in status" :key="index"
                                :card_data="card"
                            ></task-card>

                        </v-col>
                    </v-sheet>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import Vue from 'vue'
import { computed, defineComponent, isReactive, onMounted, reactive } from '@vue/composition-api'
import draggable from 'vuedraggable'
import Layout from '@/Layouts/VuetifyLayout'
import { Inertia } from '@inertiajs/inertia'
import EditDetail from './Drawer/EditDetail.vue';
import EditDataTable from './Components/EditDataTable.vue'
import { useStore } from '@/store/index'

//components
import TaskCard from '../TaskCard .vue'

export default defineComponent({
    layout: Layout,
    components: {
        TaskCard,
        draggable,
        EditDetail,
        EditDataTable,
    },
    props: {
        work_times: { type: Array },
        task_in_user: { type: Object },
    },
    setup(props) {
        const store = useStore();

        const data = reactive({
            statuses: {
                now: computed({
                    get: ()=> props.task_in_user.task_in_now,
                    set: (val)=> store.commit('', val),
                }),
                today: props.task_in_user.task_in_today,
                done: props.task_in_user.task_in_done,
                waiting: props.task_in_user.task_in_standby,
            },
            work_times: props.work_times,
        })

        const statuses_add_flag = computed((obj)=> {
            obj.forEach(arr => {
                Vue.set(arr, "show", false)
            })
        })
        // Vue.set(statuses, props.task_in_user)

        const drawer = computed({
            get: ()=> store.getters['detail/drawer'],
            set: (val)=> store.commit('detail/edit_drawer', val),
        })

        const getWorkTime = (show)=> {
            if(show.value) {
                console.log('open');
            }
        }

        const update =()=> {
            Inertia.visit('/task/update', {
                method: 'post',
                data: {
                    title: '',
                    due_date: null,
                }
            })
            Inertia.visit('/work-time/update', {
                method: 'post',
                data: {
                    title: '',
                    due_date: null,
                }
            })
        }

        // onMounted(()=> {
        //     data.statuses.now.forEach(obj => {
        //         Vue.set(obj, 'show', false)
        //     })
        //     // data.statuses.today.forEach(arr => {
        //     //     Vue.set(arr, "show", false)
        //     //     // console.log(arr);
        //     //     // console.log(isReactive(arr.show));
        //     // })
        //     data.statuses.done.forEach(obj => {
        //         Vue.set(obj, 'show', false)
        //     })
        //     data.statuses.waiting.forEach(obj => {
        //         Vue.set(obj, 'show', false)
        //     })
        // })
        return {
            data,
            drawer,
            getWorkTime,
            task_names:['now', 'today', 'waiting', 'done'],
            headers:[
                {text: '実行日', value: 'attend_date'},
                {text: '実行時間', value: 'total_time'},
            ],
            items:[
                {
                    attend_date: '2022-02-20',
                    total_time: '0h30m'
                },
                {
                    attend_date: '2022-02-21',
                    total_time: '0h39m'
                },
                {
                    attend_date: '2022-02-21',
                    total_time: '0h25m'
                },
            ]
        }
    },
})
</script>
