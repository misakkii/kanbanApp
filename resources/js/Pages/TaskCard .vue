<template>
    <v-card class="ma-1">
        <v-card-text class="pa-1">
            <div class="flex justify-between">
                <!-- プロジェクトの名前 -->
                <div>{{ card.project_name }}</div>
                <!-- 経過時間 -->
                <div>{{ card.pivot.total_hour }}h{{ card.pivot.total_minute }}m</div>
            </div>
        </v-card-text>
        <v-card-actions>
            <!-- タスクのタイトル -->
            <p class="text-h5 text--primary ma-0">{{ card.title }}</p>

            <v-spacer></v-spacer>

            <v-btn
                icon
                @click="expand_transition_exe(card.pivot.user_id, card.pivot.task_id)"
            >
                <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
            </v-btn>
        </v-card-actions>
        <!-- タスクの実行したデータを全件表示 -->
        <v-expand-transition>
            <div v-show="show">
                <v-divider></v-divider>
                <v-card-text>
                    <work-times-data-table
                        @dialog="editItem"
                        :work_times_data="data.work_times"
                    ></work-times-data-table>
                </v-card-text>
                <!-- {{data.work_times}} -->
            </div>
        </v-expand-transition>

        <v-dialog
            v-model="dialog.show"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title>タスク実行時間の変更</v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <time-picker-dialog
                                    label_name="開始時間"
                                    :time_data="dialog.props.executed_time"
                                    @time_edited="rewrite_executed_time"
                                    @restore="time_restore"
                                >
                                </time-picker-dialog>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <time-picker-dialog
                                    label_name="終了時間"
                                    :time_data="dialog.props.suspended_time"
                                    @time_edited="rewrite_suspended_time"
                                >
                                </time-picker-dialog>
                            </v-col>
                        </v-row>
                        <div>00h00m ※時間差分表示予定</div>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="cancel">Cancel</v-btn>
                    <v-btn color="primary" @click="save">save</v-btn>
                </v-card-actions>
                {{ [dialog.edited.executed_time] }}
                {{ [dialog.edited.suspended_time] }}
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import Vue from 'vue'
import { defineComponent, isReactive, reactive, toRef, watch } from '@vue/composition-api'
import moment from 'moment'

//componens
import WorkTimesDataTable from './WorkTimesDataTable.vue'
import TimePickerDialog from './TimePickerDialog.vue'

export default defineComponent({
    components: {
        WorkTimesDataTable,
        TimePickerDialog,
    },
    props: {
        card_data: {
            type: Object
        }
    },
    setup(props) {

        const data = reactive({
            card: props.card_data,
            work_times: null,
            show: false,
            dialog_delete: false,
            // task: props.task
        })

        const dialog = reactive({
            show: false,
            props: {
                executed_time: '',
                suspended_time: '',
            },
            edited: {
                executed_time: '',
                suspended_time: '',
            }
        })

        const Item = reactive({
            default: {}
        })
        const card = toRef(data, 'card')
        const show = toRef(data, 'show')
        const dialog_delete = toRef(data, 'dialog_delete')

        const editItem =(val)=> {
            dialog.show = val.show
            dialog.props.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm:00')
            dialog.props.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm:00')
            dialog.edited.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm:00')
            dialog.edited.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm:00')

            // console.log(val);
        }

        const expand_transition_exe =(user_id, task_id)=> {
            data.show = !data.show
            if(show.value) {
                axios.post('/api/get/work-time', {
                    user_id: user_id,
                    task_id: task_id,
                }).then(res => {
                    // console.log(res.data);
                    data.work_times = res.data.work_times
                }).catch(err => {
                    console.log(err);
                })
            }
        }

        const rewrite_executed_time =(time)=> {
            console.log(time);
            dialog.edited.executed_time = time
        }
        const rewrite_suspended_time =(time)=> {
            console.log(time);
            dialog.edited.suspended_time = time
        }
        const time_restore =(time)=> {
            dialog.edited.executed_time = time
        }

        const save =(time)=> {
            console.log('save');
        }

        const cancel =()=> {
            dialog.show = false
            dialog.props.executed_time = ''
            dialog.props.suspended_time = ''
        }
        // watch(dialog, ()=> {
        //     if(!dialog.show) {
        //         console.log('reset');
        //         dialog.props.executed_time = dialog.default.executed_time
        //         dialog.props.suspended_time = dialog.default.suspended_time
        //     }
        // })
        return {
            // task
            data,
            card,
            show,
            dialog,
            editItem,
            rewrite_executed_time,
            rewrite_suspended_time,
            time_restore,
            save,
            cancel,
            expand_transition_exe,
        }
    },
})
</script>
