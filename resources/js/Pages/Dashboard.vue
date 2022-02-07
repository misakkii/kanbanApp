<template>
    <v-container fluid class="pa-1" >
        <v-row>
            <!-- ユーザーの件数 -->
            <v-col
                v-for="user in task_in_today" :key="user.id"
                class="pa-1"
                cols="12"
                md="3"
            >
                <v-card
                    min-height="110%"
                >
                    <v-card-title v-text="user.last_name"></v-card-title>

                    <v-divider></v-divider>

                    <!-- ユーザーがアサインしているNowタスク -->
                    <v-col cols="12">
                        <draggable>
                        <v-card
                            v-for="n in 1" :key="n"
                            class="ma-1"
                        >
                            <v-card-text class="pa-1">
                                <div>project name</div>
                                <p class="text-h5 text--primary ma-0">title</p>
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
                                @click="drawer(today)"
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

    </v-container>
</template>

<script>
    import { computed, defineComponent, onMounted, reactive } from '@vue/composition-api'
    import Layout from '@/Layouts/VuetifyLayout'
    import Welcome from '@/Jetstream/Welcome'
    import { useStore } from '@/store/index'
    import draggable from 'vuedraggable'
    import { Inertia } from '@inertiajs/inertia'
    import { Link } from '@inertiajs/inertia-vue'


    export default defineComponent({
        layout: Layout,
        components: {
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
            },
            task_in_today: {
                type: Array
            },
        },
        setup(props) {
            // console.log(props.users)
            const store = useStore()

            // const data = reactive({
            //     users: [
            //         {
            //             id: 90,
            //             last_name: "鈴木",
            //             first_name: "太郎",
            //             now: [],
            //             //Echoからのデータの受取(全データの入れ替え※件数が少ないため), 変更条件はusers.idの一致
            //             today: [],
            //             done: [],
            //         },
            //     ]
            // })
            onMounted(()=> {
                window.Echo.private('task-added')
                .listen('TaskAdded', (e)=> {
                    user.today.push(e)
                    console.log(e);
                })
            })

            const user = reactive({
                id: 1,
                last_name: "",
                first_name: "",
                today: [],
            })

            const drawer =(val)=> {
                console.log(val)
            }

            const logout =()=> {
                this.$inertia.post(route('logout'));
                this.$inirtia.get(route('login'));
            }

            return {
                logout,
                drawer,
                user,
                test_data: [
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
                        ],
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
