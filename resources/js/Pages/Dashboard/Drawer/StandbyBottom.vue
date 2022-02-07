<template>
    <div class="text-center">
        <v-btn
            class="mb-2"
            color="blue"
            dark
            @click="sheet = !sheet"
        >
            Standby
        </v-btn>
        <v-bottom-sheet v-model="sheet">
            <v-sheet
                height="200px"
            >
                <v-data-table
                    v-model="selected"
                    :headers="headers"
                    no-data-text="データがありません。"
                    class="elevation-1"
                    single-select
                    show-select
                ></v-data-table>
            </v-sheet>
        </v-bottom-sheet>
    </div>
</template>

<script>
import { defineComponent, reactive } from '@vue/composition-api';
import { useStore } from '@/store/index';

export default defineComponent({
    setup() {
        const sotre = useStore();

        const standbys = reactive({})

        const selected = reactive({})

        return {
            sheet: false,
            selected,
            standbys,
            headers: [
                {
                    text: '作成者',
                    value: 'created_by'
                },
                {
                    text: 'タスク名',
                    value: 'title'
                },
            ]
        }
    },
})
</script>
