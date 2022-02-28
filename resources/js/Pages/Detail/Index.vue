<template>
    <v-container fluid class="pa-1">
        <v-row>
            <v-col cols="12" md="3" v-for="(status, index) in data.statuses" :key="index">
                <v-card>
                    <v-card-title>{{ index }}</v-card-title>
                    <v-sheet min-height="96">
                        <v-col cols="12">
                            <v-card v-for="(task, index) in status" :key="index" class="ma-1">
                                <v-card-text class="pa-1">
                                    <div class="flex justify-between">
                                        <div>{{ task.project_name }}</div>
                                        <div>00h00m</div>
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <p class="text-h5 text--primary ma-0">{{ task.title }}</p>

                                    <v-spacer></v-spacer>

                                    <v-btn
                                        icon
                                        @click="task.show = !task.show, getWorkTime(task.show ,task.id)"
                                    >
                                        <v-icon>{{ task.show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                    </v-btn>
                                    {{task.show}}
                                </v-card-actions>
                                <v-expand-transition>
                                    <div v-show="task.show">
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <edit-data-table></edit-data-table>
                                        </v-card-text>
                                    </div>
                                </v-expand-transition>
                            </v-card>
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

export default defineComponent({
    layout: Layout,
    components: {
        draggable,
        EditDetail,
        EditDataTable,
    },
    props: {
        work_times: {
            type: Array
        },
        task_in_user: { type: Object }
    },
    setup(props) {
        const store = useStore();

        const data = reactive({
            statuses: {
                now: props.task_in_user.task_in_now,
                today: props.task_in_user.task_in_today,
                done: props.task_in_user.task_in_done,
                waiting: props.task_in_user.task_in_standby,
            },
            // work_times: props.work_times,
            value: null,
            start: null,
            end: null,
        })

        const statuses = reactive({
            tasks: props.task_in_user
        })
        // Vue.set(statuses, props.task_in_user)

        const drawer = computed({
            get: ()=> store.getters['detail/drawer'],
            set: (val)=> store.commit('detail/edit_drawer', val),
        })

        const getWorkTime = (show, id)=> {
            if(show) {
                console.log(id)
                //非同期通信で値の取得を行う
                Inertia.get(`/detail/${id}/edit`)
                //idと同じ配列にデータを追加
                //フロント側でデータがない場合はreading表示
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
            axios.post('/task/update', {
                title: '',
                due_date: null,
            }).then(res => {
                console.log(res);
            }).catch(err => {
                console.log(err);
            })
            Inertia.visit('/project/update', {
                method: 'post',
                data: {
                    project_name: '',
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
        onMounted(()=> {
            // [data.statuses].forEach(obj => {
                // console.log(obj);
                data.statuses.now.forEach(obj => {
                    Vue.set(obj, 'show', false)
                    // console.log(obj);
                    // console.log(isReactive(obj));
                })
                data.statuses.today.forEach(arr => {
                    Vue.set(arr, "show", false)
                    // console.log(arr);
                    // console.log(isReactive(arr.show));
                })
                data.statuses.done.forEach(obj => {
                    Vue.set(obj, 'show', false)
                    // console.log(obj);
                    // console.log(isReactive(obj));
                })
                data.statuses.waiting.forEach(obj => {
                    Vue.set(obj, 'show', false)
                    // console.log(obj);
                    // console.log(isReactive(obj));
                })
            // });
        })
        return {
            data,
            show: false,
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
