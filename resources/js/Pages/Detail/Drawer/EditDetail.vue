<template>
    <v-navigation-drawer
        app
        right
        clipped
        width="40vw"
        temporary
        v-model="drawer"
    >

    <v-list>
        <v-list-item >
            <v-list-item-content >
                <v-row>
                    <v-col cols="12" md="6" class="pb-0">
                        <v-text-field label="プロジェクト名"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" class="pb-0">
                        <v-text-field label="プロジェクト期限"></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6" class="pt-0">
                        <v-text-field label="タスク名"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" class="pt-0">
                        <v-text-field label="タスク期限"></v-text-field>
                    </v-col>
                </v-row>
            </v-list-item-content>
        </v-list-item>
    </v-list>
    <div v-for="(index, n) in 10" :key="n">
        {{index}}件目 [時間差分] [日付]
        <div class="flex">
            <v-col cols="6">
                <vue-ctk-date-time-picker
                    format="YYYY-MM-DD HH:mm:00"
                    label="開始時間"
                    minuteInterval="5"
                ></vue-ctk-date-time-picker>
            </v-col>
            <v-col cols="6">
                <vue-ctk-date-time-picker
                    format="YYYY-MM-DD HH:mm:00"
                    label="終了時間"
                    minuteInterval="5"
                    right
                ></vue-ctk-date-time-picker>
            </v-col>
        </div>
        <v-divider></v-divider>
    </div>
    </v-navigation-drawer>
</template>

<script>
import { computed, defineComponent, isReactive, reactive } from '@vue/composition-api'
import { useStore } from '@/store/index'


export default defineComponent({
    props: {
        work_times: {
            type: Array
        },
        task_user: {
            type: Array
        },
    },
    setup(props) {
        const store = useStore();

        const data = reactive({
            edited_item: {
                start: null,
                end: null,
            }
        })

        console.log(props.work_times)

        const drawer = computed({
            get: ()=> store.getters['detail/drawer'],
            set: (val)=> store.commit('detail/edit_drawer', val)
        })

        return {
            drawer,
            items: props.work_times,
            headers: [
                { text: '日時', value: 'use_date' },
                { text: 'hour', value: 'hour' },
                { text: 'minute', value: 'minute' },
            ],
        }
    },
})
</script>
