<template>
    <v-container fluid class="pa-1" >
        {{ data.users[1].task_in_now }}
        <v-row>
            <!-- ユーザーの件数 -->
            <v-col
                v-for="(user, index) in data.users" :key="index"
                class="pa-1"
                cols="12"
                md="3"
            >
                <v-card
                    min-height="110%"
                >
                    <v-card-title>
                        {{user.last_name}}
                        <standby-sheet :standbys="user.task_in_standby"></standby-sheet>
                    </v-card-title>

                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているNowタスク -->
                    <v-sheet min-height="96">
                        <v-col cols="12">
                            <draggable
                                v-model="user.task_in_now"
                                draggable=".item"
                                :group="user.id"
                                :animation="250"
                                @add="onAddNow(user.now_task_count, user.id, user.task_in_now)"
                            >
                                <v-card
                                    v-for="(now, index) in user.task_in_now" :key="index"
                                    @click="select(now), selectUser(user)"
                                    :id="now.id"
                                    class="item ma-1 h-500"
                                >
                                    <v-card-text class="pa-1">
                                        <div class="flex justify-between">
                                            <div>{{ now.project_name }}</div>
                                            <div>{{ now.pivot.total_hour }}h{{ now.pivot.total_minute }}m</div>
                                        </div>
                                        <p class="text-h5 text--primary ma-0" v-text="now.title">title</p>
                                    </v-card-text>
                                </v-card>
                            </draggable>
                        </v-col>
                    </v-sheet>
                    <!-- {{[ user.now_task_count ]}} -->

                    <v-divider></v-divider>

                    <v-tabs grow v-model="user.tab">
                        <v-tab
                            v-for="item in tab_titles" :key="item"
                        >{{ item }}</v-tab>
                    </v-tabs>

                    <v-tabs-items v-model="user.tab">
                        <v-tab-item>
                            <!-- <v-card flat>
                                <v-card-text>{{ texts }} {{ user.tab }}</v-card-text>
                            </v-card> -->
                            <!-- {{texts}} -->
                            <v-col cols="12">
                                <draggable
                                    v-model="user.task_in_today"
                                    draggable =".item"
                                    :group="user.id"
                                    :animation="250"
                                    @add="onAddToday(user.task_in_today, user.id)"
                                >
                                    <v-card
                                        v-for="(today, index) in user.task_in_today" :key="index"
                                        @click="select(today), selectUser(user)"
                                        class="item back ma-1"
                                    >
                                        <v-card-text class="pa-1" >
                                            <div class="flex justify-between">
                                                <div>{{ today.project_name }}</div>
                                                <div>{{ today.pivot.total_hour }}h{{ today.pivot.total_minute }}m</div>
                                            </div>

                                            <p class="text-h5 text--primary ma-0" v-text="today.title"></p>
                                        </v-card-text>
                                    </v-card>
                                </draggable>
                            </v-col>
                        </v-tab-item>
                        <v-tab-item>
                            <v-col cols="12">
                                <draggable
                                    v-model="user.task_in_done"
                                    draggable =".item"
                                    :group="user.id"
                                    :animation="250"
                                    @add="onAddDone(user.id)"
                                    :move="()=> false"
                                >
                                    <v-card
                                        v-for="(done, index) in user.task_in_done" :key="index"
                                        @click="select(done), selectUser(user)"
                                        class="item back ma-1"
                                    >
                                        <v-card-text class="pa-1" >
                                            <div class="flex justify-between">
                                                <div>{{ done.project_name }}</div>
                                                <div>{{ (done.pivot.total_work_minute - (done.pivot.total_work_minute % 60)) / 60 }}h{{ done.pivot.total_work_minute % 60 }}m</div>
                                            </div>

                                            <p class="text-h5 text--primary ma-0" v-text="done.title"></p>
                                        </v-card-text>
                                    </v-card>
                                </draggable>
                            </v-col>
                        </v-tab-item>
                    </v-tabs-items>

                    <!-- ユーザーがアサインしているTodayタスク -->
                    <!-- <v-col cols="12">
                        <draggable>
                            <v-card
                                v-for="today in user.task_in_today" :key="today.id"
                                @click="select(today), selectUser(user)"
                                class="ma-1"
                            >
                                <v-card-text class="pa-1">
                                    <div v-text="today.project_name"></div>
                                    <p class="text-h5 text--primary ma-0" v-text="today.title"></p>
                                </v-card-text>
                            </v-card>
                        </draggable>
                    </v-col> -->
                </v-card>
            </v-col>
        </v-row>
        <edit-dashboard />
        <v-snackbar
            v-model="data.snackbar"
            dark
        >{{ sb_message }}</v-snackbar>

    </v-container>
</template>

<script>
    import Vue from 'vue'
    import { computed, defineComponent, isReactive, onMounted, reactive, watch } from '@vue/composition-api'
    import Layout from '@/Layouts/VuetifyLayout'
    import Welcome from '@/Jetstream/Welcome'
    import EditDashboard from './Drawer/EditDashboard.vue'
    import StandbySheet from './Drawer/StandbyBottom.vue'
    import { useStore } from '@/store/index'
    import draggable from 'vuedraggable'
    import { Inertia } from '@inertiajs/inertia'
    import { Link } from '@inertiajs/inertia-vue'




    export default defineComponent({
        layout: Layout,
        components: {
            Link,
            Welcome,
            EditDashboard,
            StandbySheet,
            draggable,
        },
        props: {
            auth: { type: Array },
            users_tasks: { type: Array },
            projects: { type: Array },
        },
        setup(props) {
            console.log(props.users_tasks)
            const store = useStore()

            const data = reactive({
                snackbar: computed({
                    get: ()=> store.getters['dashboard/snackbar'],
                    set: (val)=> store.commit('dashboard/snackbar', val),
                }),
                now_task_count: computed({
                    get: ()=> store.getters['dashboard/now_task_count'],
                    set: (val)=> store.commit('dashboard/now_task_count', val)
                }),
                users: props.users_tasks,
            })

            // console.log(isReactive(data.users[0].task_in_now))
            //watchで再定義
            const selectUser = (val)=> {
                data.now_task_count = val.task_in_now.length
                // console.log(data.now_task_count)
                store.commit('dashboard/now_task_count', data.now_task_count)
                // console.log(val)
            }

            const drawer = computed({
                get: () => store.getters['dashboard/edit_drawer'],
                set: (val) => store.commit('dashboard/edit_drawer_op', val)
            })

            const select =(val)=> {
                drawer.value = !drawer.value
                // console.log(val)
                store.commit('dashboard/projects', props.projects)
                store.commit('dashboard/project_id', val.project_id)
                store.commit('dashboard/title', val.title)
                store.commit('dashboard/due_date', val.due_date)
                store.commit('dashboard/select_data', val)
                store.commit('dashboard/now_task_count', data.now_task_count)

                // console.log(props.projects)
            }

            const logout =()=> {
                this.$inertia.post(route('logout'));
                this.$inirtia.get(route('login'));
            }

            const start =(count,)=> {
                // console.log(now_count.value)
                if(count >= 1) {
                    let text = "実行できるタスクは1件までです"
                    store.commit('dashboard/snackbar_message', text)
                    data.snackbar.value = true
                } else {
                    Inertia.visit('task/execute', {
                        method : 'post',
                        data: {
                            task_id: form.task_id,
                            user_id: form.user_id,
                        }
                    })
                    drawer.value = !drawer.value
                    // console.log("実行中のタスクはありません")
                }
            }

            const onAddNow =(count, user_id, now)=> {
                // now.forEach(arr => {
                //     console.log(arr.pivot.task_id)
                //     arr.pivot.task_id

                //     Inertia.visit('task/execute', {
                //         method: 'post',
                //         data: {
                //             user_id: user_id,
                //             task_id: arr.pivot.task_id,
                //         }
                //     })
                // })
                // console.log(now)
                // console.log(user_id)

                if(count > 1) {
                    data.snackbar = !data.snackbar
                    Inertia.visit('task/execute', {
                        method: 'post',
                        data: {}
                    })
                } else {
                    now.forEach(arr => {
                        console.log(arr.pivot.task_id)
                        arr.pivot.task_id

                        Inertia.visit('task/execute', {
                            method: 'post',
                            data: {
                                user_id: user_id,
                                task_id: arr.pivot.task_id,
                            }
                        })
                    })
                }
                // console.log(e.item)
            }

            const onAddToday =(today, user_id)=> {
                console.log(today)
                today.forEach(arr => {
                    console.log(arr)
                    Inertia.visit('task/suspend', {
                        method: 'post',
                        data: {
                            user_id: user_id,
                        }
                    })
                })
            }
            const onAddDone =(user_id)=> {
                console.log('タスクが完了しました')
                Inertia.visit('task/complete', {
                    method: 'post',
                    data: {
                        user_id: user_id,
                    }
                })
            }

            const group = computed({
                get: ()=> null,
                set: (id)=> {
                    if(id) {
                        console.log()
                        return id
                    }else{
                        return null
                    }
                }
            })
            watch(data.users, ()=> {
                data.users.forEach(obj => {
                    obj.now_task_count = obj.task_in_now.length
                });
            })

            onMounted(()=> {
                data.users.forEach(obj => {
                    Vue.set(obj, "tab", 0)
                    Vue.set(obj, "now_task_count", obj.task_in_now.length)
                    // arr["now_task_count"] = arr.task_in_now.length
                    console.log(obj)
                });
                data.users["status"] = ["today", "done"]
                store.commit('dashboard/users_tasks', props.users_tasks)

                // window.Echo.private('task-added')
                // .listen('TaskAdded', (e)=> {
                //     user.today.push(e)
                //     console.log(e);
                // })
            })

            return {
                data,
                // task: null,

                sb_message: computed(()=> store.getters['dashboard/snackbar_message']),
                select,
                selectUser,
                onAddNow,
                onAddToday,
                onAddDone,
                group,
                logout,
                tab_titles: ['Today', 'done'],
            }
        },
    })
</script>
