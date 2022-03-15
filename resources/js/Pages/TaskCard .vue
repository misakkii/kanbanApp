<template>
    <v-card class="ma-1">
        <validation-observer>
            <form>
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
                                            :difference="dialog.props.difference"
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
                                            :difference="dialog.props.difference"
                                            @time_edited="rewrite_suspended_time"
                                        >
                                        </time-picker-dialog>
                                    </v-col>

                                    <v-col
                                        v-for="(data, index) in dialog.test" :key="index"
                                        cols="12"
                                        md="6"
                                    >
                                        <time-picker-dialog
                                            :label_name="data.label"
                                            :time_data="data.time"
                                            :difference="data.diff"
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
                        {{ [dialog.test.execueted.diff] }}
                    </v-card>
                </v-dialog>
            </form>
        </validation-observer>
    </v-card>
</template>

<script>
import Vue from 'vue'
import { defineComponent, isReactive, reactive, toRef, watch } from '@vue/composition-api'
import moment from 'moment'
import { required } from 'vee-validate/dist/rules'

//componens
import { extend, ValidationProvider, ValidationObserver } from 'vee-validate';
import WorkTimesDataTable from './WorkTimesDataTable.vue'
import TimePickerDialog from './TimePickerDialog.vue'

extend('required', {
    ...required,
    message: '{_field_}の入力は必須です',
})

export default defineComponent({
    components: {
        WorkTimesDataTable,
        TimePickerDialog,
        ValidationObserver,
        ValidationProvider,
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
                diff: null,
            },
            id: null,
            test: {
                execueted: {
                    label: "開始時間",
                    time: '',
                    diff: null,
                    edit: '',
                },
                suspended: {
                    label: "終了時間",
                    time: '',
                    diff: null,
                    edit: '',
                },
            },
            edited: {
                executed_time: '',
                suspended_time: '',
            }
        })

        const card = toRef(data, 'card')
        const show = toRef(data, 'show')
        const dialog_delete = toRef(data, 'dialog_delete')

        const editItem =(val)=> {
            dialog.show = val.show
            dialog.id = val.data.id
            console.log(val.data);
            dialog.props.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.props.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            dialog.edited.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.edited.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')

            dialog.test.execueted.time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.test.suspended.time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            dialog.test.execueted.edit = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.test.suspended.edit = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            let excuted_time = new Date(dialog.test.execueted.time).getTime()
            let suspended_time = new Date(dialog.test.suspended.time).getTime()
            dialog.test.execueted.diff = suspended_time - excuted_time
            dialog.test.suspended.diff = suspended_time - excuted_time
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
            dialog.test.execueted.time = time
        }
        const rewrite_suspended_time =(time)=> {
            console.log(time);
            dialog.edited.suspended_time = time
            dialog.test.suspended.time = time
        }
        const time_restore =(time)=> {
            dialog.edited.executed_time = time
        }
        const save =(time)=> {
            console.log('save');
            axios.post('/api/detail/update', {
                id: dialog.id,
                excuted_time: '2022-03-14 11:33:00',
                suspended_time: dialog.test.suspended.edit,
            }).then(res => {
                console.log(res.data);
                let excuted_time = new Date(res.data.request.excuted_time).getTime()
                let suspended_time = new Date(res.data.request.suspended_time).getTime()
                dialog.props.diff = suspended_time - excuted_time
                dialog.test.execueted.diff = suspended_time - excuted_time
                console.log(suspended_time - excuted_time);
            }).catch(err => {
                console.log(err.data);
            })
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
