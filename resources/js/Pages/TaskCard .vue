<template>
    <v-card class="ma-1">
        <validation-observer v-slot="{ invalid }">
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
                                        v-for="(data, index) in dialog.picker.data" :key="index"
                                        cols="12"
                                        md="6"
                                    >
                                        <v-dialog
                                            v-model="data.show"
                                            max-width="500px"
                                            persistent
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <validation-provider
                                                    v-slot="{ errors }"
                                                    :name="data.label"
                                                    :rules="data.rules"
                                                    :ref="data.ref"
                                                >
                                                    <v-text-field
                                                        v-model="data.edit"
                                                        :error-messages="errors && data.err_msg"
                                                        :label="data.label"
                                                        readonly

                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </validation-provider>
                                            </template>
                                            <VueCtkDateTimePicker
                                                v-model="data.edit"
                                                :inline="true"
                                                format="YYYY-MM-DD HH:mm:ss"
                                                minuteInterval="5"
                                            ></VueCtkDateTimePicker>
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col cols="12" md="4" class="pa-0">
                                                        <v-btn block @click="data.cancel">cancel</v-btn>
                                                    </v-col>

                                                    <v-col cols="12" md="4" class="pa-0">
                                                        <v-btn block @click="data.back">back</v-btn>
                                                    </v-col>

                                                    <v-col cols="12" md="4" class="pa-0">
                                                        <v-btn block @click="data.save">保存</v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-dialog>
                                    </v-col>
                                    <!-- {{dialog.picker.data.execueted.rules}} -->
                                </v-row>
                                <div>00h00m ※時間差分表示予定</div>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="cancel.editDialog">Cancel</v-btn>
                            <v-btn color="primary" @click="save.method" :disabled="invalid || save.disabled">save</v-btn>
                        </v-card-actions>
                        {{ [dialog.picker.data.execueted.edit] }}
                        {{ [dialog.picker.data.suspended.edit] }}
                        {{ [dialog.picker.diff] }}
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
import { required, max_value } from 'vee-validate/dist/rules'

//componens
import { extend, ValidationProvider, ValidationObserver, setInteractionMode } from 'vee-validate';
import WorkTimesDataTable from './WorkTimesDataTable.vue'
import TimePickerDialog from './TimePickerDialog.vue'

setInteractionMode('eager')

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
            edited: {
                executed_time: '',
                suspended_time: '',
            },
            work_time_id: null,
            task_id: null,
            user_id: null,
            picker: {
                show: false,
                diff: null,
                data: {
                    execueted: {
                        show: false,
                        rules: `required`,
                        err_msg: '',
                        label: "開始時間",
                        default: '',
                        select: '',
                        edit: '',
                        diff: null,
                        cancel: ()=> {
                            dialog.picker.data.execueted.show = false
                            dialog.picker.data.execueted.edit = dialog.picker.data.execueted.select
                        },
                        back: ()=> {
                            dialog.picker.data.execueted.edit = dialog.picker.data.execueted.default
                        },
                        save: ()=> {
                            dialog.picker.data.execueted.show = false
                            dialog.picker.data.execueted.select = dialog.picker.data.execueted.edit
                        },
                    },
                    suspended: {
                        show: false,
                        rules: 'required',
                        err_msg: '',
                        label: "終了時間",
                        default: '',
                        select: '',
                        edit: '',
                        diff: null,
                        cancel: ()=> {
                            dialog.picker.data.suspended.show = false
                            dialog.picker.data.suspended.edit = dialog.picker.data.suspended.select
                        },
                        back: ()=> {
                            dialog.picker.data.suspended.edit = dialog.picker.data.suspended.default
                        },
                        save: ()=> {
                            dialog.picker.data.suspended.show = false
                            dialog.picker.data.suspended.select = dialog.picker.data.suspended.edit
                            let excuted_time = new Date(dialog.picker.data.execueted.edit).getTime()
                            let suspended_time = new Date(dialog.picker.data.suspended.edit).getTime()
                            dialog.picker.diff = suspended_time - excuted_time
                        },
                    },
                },
            },
        })

        const card = toRef(data, 'card')
        const show = toRef(data, 'show')
        const dialog_delete = toRef(data, 'dialog_delete')

        const editItem =(val)=> {
            dialog.show = val.show
            dialog.work_time_id = val.data.id
            dialog.task_id = val.data.task_id
            dialog.user_id = val.data.user_id
            console.log(val.data);
            dialog.props.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.props.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            dialog.edited.executed_time = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.edited.suspended_time = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')

            dialog.picker.data.execueted.default = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.picker.data.execueted.edit = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.picker.data.execueted.select = moment(val.data.executed_time).format('YYYY-MM-DD kk:mm')
            dialog.picker.data.suspended.default = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            dialog.picker.data.suspended.edit = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')
            dialog.picker.data.suspended.select = moment(val.data.suspended_time).format('YYYY-MM-DD kk:mm')


            let excuted_time = new Date(dialog.picker.data.execueted.edit).getTime()
            let suspended_time = new Date(dialog.picker.data.suspended.edit).getTime()
            dialog.picker.data.execueted.diff = suspended_time - excuted_time
            dialog.picker.data.suspended.diff = suspended_time - excuted_time
            dialog.picker.diff = suspended_time - excuted_time
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
            dialog.picker.data.execueted.time = time
        }
        const rewrite_suspended_time =(time)=> {
            console.log(time);
            dialog.edited.suspended_time = time
            dialog.picker.data.suspended.time = time
        }
        const time_restore =(time)=> {
            dialog.edited.executed_time = time
        }
        // const save =(time)=> {
        //     console.log('save');
        //     axios.post('/api/detail/update', {
        //         id: dialog.id,
        //         excuted_time: '2022-03-14 11:30:00',
        //         suspended_time: '2022-03-14 11:35:00',
        //     }).then(res => {
        //         console.log(res.data);
        //         let excuted_time = new Date(res.data.request.excuted_time).getTime()
        //         let suspended_time = new Date(res.data.request.suspended_time).getTime()
        //         dialog.props.diff = suspended_time - excuted_time
        //         dialog.picker.data.execueted.diff = suspended_time - excuted_time
        //         console.log(suspended_time - excuted_time);
        //     }).catch(err => {
        //         console.log(err.data);
        //     })
        // }
        const save =reactive({
            disabled: false,
            method: ()=> {
                console.log('save');
                save.disabled = true
                axios.post('/api/work-time/update', {
                    work_time_id: dialog.work_time_id,
                    task_id: dialog.task_id,
                    user_id: dialog.user_id,
                    executed_time: dialog.picker.data.execueted.edit,
                    suspended_time: dialog.picker.data.suspended.edit,
                }).then(res => {
                    console.log(res.data);
                    if(res.data.success) {
                        dialog.show = false
                        dialog.picker.data.suspended.err_msg = ""
                        dialog.picker.data.execueted.err_msg = ""
                    }
                    if(res.data.err_msg) {
                        dialog.picker.data.suspended.err_msg = res.data.err_msg
                        dialog.picker.data.execueted.err_msg = res.data.err_msg
                    }

                    if(res.data.executed_time[0] && res.data.suspended_time[0]) {
                        dialog.picker.data.suspended.err_msg = res.data.suspended_time[0]
                        dialog.picker.data.execueted.err_msg = res.data.executed_time[0]
                    }
                    console.log('test')
                    let excuted_time = new Date(res.data.request.excuted_time).getTime()
                    let suspended_time = new Date(res.data.request.suspended_time).getTime()

                    // dialog.props.diff = suspended_time - excuted_time
                    // dialog.picker.data.execueted.diff = suspended_time - excuted_time
                    // console.log(suspended_time - excuted_time);

                }).catch(err => {
                    console.log(err.data);
                })
            }
        })

        const cancel =reactive({
            editDialog: ()=> {
                dialog.show = false
                dialog.props.executed_time = ''
                dialog.props.suspended_time = ''
                dialog.picker.data.suspended.err_msg = ""
                dialog.picker.data.execueted.err_msg = ""
            },
        })

        watch(dialog.picker.data.suspended, ()=> {
            save.disabled = false
        })
        watch(dialog.picker.data.execueted, ()=> {
            save.disabled = false
        })
        return {
            name: "",
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
