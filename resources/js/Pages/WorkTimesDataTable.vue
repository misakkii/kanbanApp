<template>
    <v-data-table
        hide-default-footer
        :headers="headers"
        :items="data.desserts"
    >
        <v-dialog v-model="dialog_delete">
            <v-card>
                <v-card-title>削除</v-card-title>
            </v-card>
        </v-dialog>
        <template v-slot:item="{ item }">
            <tr>
                <td>{{ item.use_date }}</td>
                <td>{{ item.hour }}h{{item.minute}}m</td>
                <td>
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                    >mdi-pencil</v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >mdi-delete</v-icon>
                </td>
            </tr>
        </template>
    </v-data-table>
</template>


<script>
import { defineComponent, reactive, toRef, watch } from '@vue/composition-api'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: {
        work_times_data: {
            type: Array
        }
    },
    setup(props, { emit }) {
        const data = reactive({
            dialog: true,
            dialog_delete: true,
            desserts: [],
        })
        const dialog = toRef(data, 'dialog')
        const dialog_delete = toRef(data, 'dialog_delete')

        const editItem =(item)=> {
            let dialog = {
                show: true,
                data: item,
            }
            emit('dialog', dialog)
            // console.log(item);
        }

        const deleteItem =(item)=> {
            console.log(item);
            let exe = confirm('削除しますか？')
            if(exe) {
                console.log('削除を実行');
                axios.post(`/api/work-time/${item.id}/destroy`)
                .then(res => {
                    console.log(res.data);
                }).catch(err => {
                    console.log(err.data);
                })
            }
        }

        watch(props, ()=> {
            data.desserts = props.work_times_data
        })

        return {
            data,
            dialog,
            dialog_delete,
            editItem,
            deleteItem,
            headers: [
                {text: "実行日", value: 'use_date'},
                {text: "実行時間", value: 'hour'},
                {text: "Actions", value: 'actions', sortable: false},
            ],
            desserts: [
                {
                    use_date: '2022/03/02',
                    hour: '0h30'
                }
            ],
        }
    },
})
</script>
