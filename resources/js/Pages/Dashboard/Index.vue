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
                    <v-card-title v-text="user.last_name"></v-card-title>
                    <standby-sheet></standby-sheet>

                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているNowタスク -->
                    <v-col cols="12">
                        <draggable>
                        <v-card
                            v-for="now in user.task_in_now" :key="now.id"
                            @click="select(now)"
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

                    <!-- ユーザーがアサインしているTodayタスク -->
                    <v-col cols="12">
                        <draggable>
                            <v-card
                                v-for="today in user.task_in_today" :key="today.id"
                                @click="select(today)"
                                class="ma-1"
                            >
                                <v-card-text class="pa-1">
                                    <div v-text="today.project_name"></div>
                                    <p class="text-h5 text--primary ma-0" v-text="today.title"></p>
                                </v-card-text>
                            </v-card>
                        </draggable>
                    </v-col>

                </v-card>
            </v-col>
        </v-row>
        <edit-dashboard />

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
            users: { type: Array },
            users_tasks: { type: Array },
            projects: { type: Array },
        },
        setup(props) {
            console.log(props.users_tasks)
            const store = useStore()

            onMounted(()=> {
                window.Echo.private('task-added')
                .listen('TaskAdded', (e)=> {
                    user.today.push(e)
                    console.log(e);
                })
            })

            const drawer = computed({
                get: () => store.getters['dashboard/edit_drawer'],
                set: (val) => store.commit('dashboard/edit_drawer_op', val)
            })

            const projects_data = reactive(props.projects)

            const select =(val)=> {
                drawer.value = !drawer.value
                console.log(val)
                store.commit('dashboard/title', val.title)
                store.commit('dashboard/due_date', val.due_date)
                store.commit('dashboard/projects', props.projects)
                store.commit('dashboard/project_id', val.project_id)
                store.commit('dashboard/task_id', val.id)
                store.commit('dashboard/select_data', val)

                // console.log(props.projects)
            }

            const logout =()=> {
                this.$inertia.post(route('logout'));
                this.$inirtia.get(route('login'));
            }

            return {
                select,
                logout,
            }
        },
    })
</script>
