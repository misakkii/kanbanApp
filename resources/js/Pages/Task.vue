<template>
    <Layout>
        <top />
    </Layout>
</template>

<script>
import { defineComponent } from '@vue/composition-api'
import Layout from '@/Layouts/VuetifyLayout.vue'
import Top from '../Pages/Top.vue'


export default defineComponent({
    components: {
        Layout,
        Top,
    },
    setup() {

    },
})
</script>
