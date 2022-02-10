<template>
    <div class="text-center">
        <v-btn
            color="blue"
            dark
            @click="data.sheet = !data.sheet"
        >
            Standby
        </v-btn>
        <v-bottom-sheet v-model="data.sheet">
            <v-sheet
                height="340"
            >
                <v-card class="pa-2">

                    <v-card-title>
                        スタンバイ一覧
                        <v-btn color="primary" @click="chengeToToday">todayに追加</v-btn>
                    </v-card-title>
                    <v-data-table
                        v-model="data.selected"
                        :headers="headers"
                        :items="standbys"
                        item-key="id"
                        no-data-text="データがありません。"
                        class="elevation-1"
                        height="200"
                    >
                        <template v-slot:item="{ item }">
                            <tr :class="data.selected.indexOf(item.id)>-1?'cyan':''" @click="selectStandby(item)">
                                <td>{{ item.created_by }}</td>
                                <td>{{ item.title }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card>
            </v-sheet>
        </v-bottom-sheet>
        {{ data.selected }}
        {{ data.user_id }}
    </div>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api';
import { useStore } from '@/store/index';
import { Inertia } from '@inertiajs/inertia';

export default defineComponent({
    props: {
        standbys: {
            type: Array
        },
    },
    setup(props) {
        const store = useStore();

        const data = reactive({
            sheet: false,
            user_id:  null,
            selected: [],
        })

        const selectStandby = (row)=> {
            // console.log(row.pivot.user_id)
            toggleSelection(row.id)
            data.user_id = row.pivot.user_id
        }
        const toggleSelection =(taskID)=>{
            if(data.selected.includes(taskID)) {
                // console.log('同じ値があります')
                data.selected = data.selected.filter(
                    selectedTaskID => selectedTaskID !== taskID
                );
            } else {
                data.selected.push(taskID)
            }
        }


        const chengeToToday = ()=> {
            Inertia.visit('/dashboard/chengeToToday', {
                method: 'post',
                data: {
                    user_id: data.user_id,
                    task_id: data.selected,
                }
            })
        }

        return {
            data,
            selectStandby,
            chengeToToday,
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
