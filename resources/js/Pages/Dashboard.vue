<template>

    <vuetify-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

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
    import { defineComponent, reactive } from '@vue/composition-api'
    import AppLayout from '@/Layouts/AppLayout'
    import VuetifyLayout from '@/Layouts/VuetifyLayout'
    import Welcome from '@/Jetstream/Welcome'
    import { Link } from '@inertiajs/inertia-vue'
    import Top from './Top.vue'

    export default defineComponent({
        components: {
            AppLayout,
            VuetifyLayout,
            Top,
            Link,
            Welcome,
        },
        setup() {
            const data = reactive({
                mini: true,
                nav_list:[]
            })
            const logout =()=> {
                this.$inertia.post(route('logout'));
                this.$inirtia.get(route('login'));
            }
            return {
                data,
                logout,
            }
        },
    })
</script>
