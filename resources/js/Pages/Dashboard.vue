<template>

    <vuetify-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        {{auth}}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <welcome />
                </div>
            </div>
        </div>
    </vuetify-layout>

</template>

<script>
    import { computed, defineComponent, reactive } from '@vue/composition-api'
    import AppLayout from '@/Layouts/AppLayout'
    import VuetifyLayout from '@/Layouts/VuetifyLayout'
    import Welcome from '@/Jetstream/Welcome'
    import { Link } from '@inertiajs/inertia-vue'
    import Top from './Top.vue'
    import { useStore } from '../store/index'

    export default defineComponent({
        components: {
            AppLayout,
            VuetifyLayout,
            Top,
            Link,
            Welcome,
        },
        props: {
            auth: {
                type: Array
            }
        },
        setup(props) {
            console.log(props.auth)
            const store = useStore()

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
            }
        },
    })
</script>
