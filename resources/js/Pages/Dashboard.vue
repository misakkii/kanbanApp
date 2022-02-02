<template>
    <v-container fluid class="pa-1" >
        <v-row>
            <!-- ユーザーの件数 -->
            <v-col
                v-for="user in data" :key="user.id"
                class="pa-1"
                cols="12"
                md="3"
            >
                <v-card
                    class="overflow-y-auto mx-auto"
                >
                    <v-card-title v-text="user.last_name"></v-card-title>

                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているタスク -->
                    <v-col cols="12">
                        <draggable :options="{group:user.id}">
                        <v-card
                            v-for="task in user.now" :key="task.id"
                            class="ma-1"
                        >
                            <v-card-text class="pa-1">
                                <div v-text="now.project_name">project name</div>
                                <p class="text-h5 text--primary ma-0" v-text="now.title"></p>
                            </v-card-text>
                        </v-card>
                        </draggable>
                    </v-col>

                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているタスク -->
                    <v-col cols="12">
                        <draggable :options="{group: user.id}">
                            <v-card
                                v-for="task in user.tasks" :key="task.id"
                                class="ma-1"
                            >
                                <v-card-text class="pa-1">
                                    <div v-text="task.project_name">project name</div>
                                    <p class="text-h5 text--primary ma-0" v-text="task.title"></p>
                                </v-card-text>
                            </v-card>
                        </draggable>
                    </v-col>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { computed, defineComponent, reactive } from '@vue/composition-api'
    import AppLayout from '@/Layouts/AppLayout'
    import Layout from '@/Layouts/VuetifyLayout'
    import Welcome from '@/Jetstream/Welcome'
    import { Link } from '@inertiajs/inertia-vue'
    import Top from './Top.vue'
    import { useStore } from '../store/index'
    import draggable from 'vuedraggable'

    export default defineComponent({
        layout: Layout,
        components: {
            AppLayout,
            Top,
            Link,
            Welcome,
            draggable,
        },
        props: {
            auth: {
                type: Array
            },
            users: {
                type: Array
            }
        },
        setup(props) {
            console.log(props.users)
            const store = useStore()

            const users_data = props.users

            const user = reactive({
                id: computed({
                    get: ()=> store.getters['user/id'],
                    set: (val)=> store.commit('user/id', val),
                }),
                last_name: computed({
                    get: ()=> store.getters['user/last_name'],
                    set: (val)=> store.commit('user/last_name', val),
                }),
                first_name: computed({
                    get: ()=> store.getters['user/first_name'],
                    set: (val)=> store.commit('user/first_name', val),
                }),
            })
            user.id = props.auth[0].id
            user.last_name = props.auth[0].last_name
            user.first_name = props.auth[0].first_name
            console.log(user)

            const logout =()=> {
                this.$inertia.post(route('logout'));
                this.$inirtia.get(route('login'));
            }

            return {
                logout,
                users_data,
                data: [
                    {
                        id: 1,
                        last_name: "上野",
                        now: [],
                        tasks: [
                            {
                                id: 1,
                                project_name: "上野特別プロジェクト",
                                title: "上野１"
                            },
                            {
                                id: 2,
                                project_name: "上野特別プロジェクト",
                                title: "上野２"
                            },
                        ]
                    },
                    {
                        id: 2,
                        last_name: "北原",
                        now: [],
                        tasks: [
                            {
                                id: 1,
                                project_name: "上野特別プロジェクト",
                                title: "北原１"
                            },
                            {
                                id: 2,
                                project_name: "上野特別プロジェクト",
                                title: "北原２"
                            },
                            {
                                id: 3,
                                project_name: "北原最高!!!",
                                title: "北原３"
                            }
                        ]
                    },
                    {
                        id: 3,
                        last_name: "成田",
                        now: [],
                        tasks: [
                            {
                                id: 1,
                                project_name: "上野特別プロジェクト",
                                title: "成田１"
                            },
                            {
                                id: 2,
                                project_name: "北原最高!!!",
                                title: "成田２"
                            }
                        ]
                    },
                ]
            }
        },
    })
</script>
