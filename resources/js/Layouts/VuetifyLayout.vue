<template>
    <v-app>
        <!-- left -->
        <v-navigation-drawer
            app
            permanent
            :mini-variant="data.mini"
            :expand-on-hover="data.mini"
            color="primary"
            dark>
            <v-list-item>
                <v-list-item-title class="title">
                    {{ $page.props.user.last_name }}{{ $page.props.user.first_name }}
                </v-list-item-title>
                <v-btn icon v-if="!data.mini">
                    <v-icon
                        @click.stop="data.mini = !data.mini"
                    >mdi-backburger</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider />

            <v-list nav dense>
                <Link as="v-list-item" href="http://localhost/dashboard">
                    <v-list-item-icon>
                        <v-icon>mdi-account-multiple-check</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Dashboard
                    </v-list-item-content>
                </Link>
                <Link as="v-list-item" href="http://localhost/detail">
                    <v-list-item-icon>
                        <v-icon>mdi-account-check</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Detail
                    </v-list-item-content>
                </Link>

                <v-list-group
                    no-action
                    v-for="nav_list in data.nav_lists" :key="nav_list.name"
                    :prepend-icon="nav_list.icon"
                    :append-icon="nav_list.lists ? undefined : ''"
                    color="white"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title v-text="nav_list.name"></v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <Link
                        v-for="list in nav_list.lists" :key="list.name"
                        as="v-list-item"
                        :href="list.link"
                    >
                        <v-list-item-content>
                            <v-list-item-title v-text="list.name"></v-list-item-title>
                        </v-list-item-content>
                    </Link>

                </v-list-group>
            </v-list>
        </v-navigation-drawer>


        <!-- right -->
        <add-project />
        <edit-project />
        <edit-task />

        <v-app-bar
            app
            clipped-right
            color="primary"
            dark>
            <v-app-bar-nav-icon
                v-if="data.mini"
                @click.stop="data.mini = !data.mini">
            </v-app-bar-nav-icon>
            <v-toolbar-title>dashboard</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-menu dark>
                <template v-slot:activator="{ on }">
                    <v-btn
                        icon
                        v-on="on"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>
                <v-list dark>
                    <v-form>
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon>mdi-account</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <Link :href="route('profile.show')" as="v-list-item-title">プロフィール</Link>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon>mdi-logout</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <Link :href="route('logout')" method="post" as="v-list-item-title">ログアウト</Link>
                        </v-list-item-content>
                    </v-list-item>
                    </v-form>
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main>
            <v-container fluid>
                <slot></slot>
            </v-container>
        </v-main>

    </v-app>
</template>

<script>
    import { defineComponent, reactive } from '@vue/composition-api'
    import Welcome from '@/Jetstream/Welcome'
    import AddProject from '../Pages/Project/Drawers/AddProject.vue'
    import EditProject from '../Pages/Project/Drawers/EditProject.vue'
    import EditTask from'@/Pages/Task/Drawer/EditTask.vue'
    import { Link } from '@inertiajs/inertia-vue'

    export default defineComponent({
        components: {
            Link,
            Welcome,
            AddProject,
            EditProject,
            EditTask,
        },
        setup() {
            const data = reactive({
                mini: true,
                nav_lists: [
                    // {
                    //     name:"全体",
                    //     icon: "mdi-account-multiple-check",
                    //     Link: "/task/dashboard"

                    // },
                    {
                        name: "タスク",
                        icon: "mdi-account",
                        link: "",
                        lists: [
                            {
                                name: "一覧",
                                link: "/task/list",
                                link_name: "task.create",
                            },
                            {
                                name: "完了した一覧",
                                link: "/task/list/completed",
                                link_name: "task.create.completed",
                            },
                            {
                                name: "削除した一覧",
                                link: "/task/list/deleted",
                                link_name: "task.create.deleted",
                            },
                        ],
                    },
                    {
                        name: "プロジェクト",
                        icon: "mdi-file-multiple",
                        link: "",
                        lists: [
                            {
                                name: "一覧",
                                link: "/project/list",
                                link_name: "project",
                            },
                            {
                                name: "完了一覧",
                                link: "/project/list/completion",
                                link_name: "project.completion",
                            },
                            {
                                name: "削除一覧",
                                link: "/project/list/delete",
                                link_name: "project.completion",
                            },
                        ],
                    },
                ],
            })
            return {
                data,
            }
        },
    })
</script>
