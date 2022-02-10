<template>
    <v-container fluid class="pa-1" >
        <v-row>
            <!-- ユーザーの件数 -->
            <v-col
                v-for="user in users_tasks" :key="user.id"
                class="pa-1"
                cols="12"
                md="3"
            >
                <v-card
                    min-height="110%"
                >
                    <v-card-title>
                        {{user.last_name}}
                        {{ [data.snackbar] }}
                        <standby-sheet :standbys="user.task_in_standby"></standby-sheet>
                    </v-card-title>


                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているNowタスク -->
                    <v-col cols="12">
                        <draggable>
                        <v-card
                            v-for="now in user.task_in_now" :key="now.id"
                            @click="select(now), selectUser(user)"
                            class="ma-1"
                        >
                            <v-card-text class="pa-1">
                                <div v-text="now.project_name">project name</div>
                                <p class="text-h5 text--primary ma-0" v-text="now.title">title</p>
                            </v-card-text>
                        </v-card>
                        </draggable>
                    </v-col>

                    <v-divider></v-divider>
                    <v-tabs grow v-model="user.tab">
                        <v-tab
                            v-for="item in tab_titles" :key="item"
                        >{{ item }}</v-tab>
                    </v-tabs>

                    <v-tabs-items v-model="user.tab">
                        <v-tab-item
                            v-for="(texts, index) in user.t_d_data" :key="index"
                        >
                            <!-- <v-card flat>
                                <v-card-text>{{ texts }} {{ user.tab }}</v-card-text>
                            </v-card> -->
                            <v-col cols="12">
                                <draggable>
                                    <v-card
                                        v-for="task in texts" :key="task.id"
                                        @click="select(task), selectUser(user)"
                                        class="ma-1"
                                    >
                                        <v-card-text class="pa-1">
                                            <div v-text="task.project_name"></div>
                                            <p class="text-h5 text--primary ma-0" v-text="task.title"></p>
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
    import { computed, defineComponent, onMounted, reactive } from '@vue/composition-api'
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
            //watchで再定義
            const selectUser = (val)=> {
                data.now_task_count = val.task_in_now.length
                // console.log(data.now_task_count)
                store.commit('dashboard/now_task_count', data.now_task_count)
                // console.log(val)
            }

            onMounted(()=> {
                data.users.forEach(arr => {
                    arr["tab"] = 0
                    arr["t_d_data"] = [arr.task_in_today, arr.task_in_done]
                    console.log(arr)
                });
                // data.users["status"] = ["today", "done"]
                store.commit('dashboard/users_tasks', props.users_tasks)

                window.Echo.private('task-added')
                .listen('TaskAdded', (e)=> {
                    user.today.push(e)
                    console.log(e);
                })
            })

            const test = computed({
                get: ()=> store.getters['dashboard/users_tasks']
            })
            // console.log(test.value)

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

            return {
                data,
                sb_message: computed(()=> store.getters['dashboard/snackbar_message']),
                select,
                selectUser,
                logout,
                tab_titles: ['Today', 'done'],
                texts: ['text1', 'text2']
            }
        },
    })
</script>
