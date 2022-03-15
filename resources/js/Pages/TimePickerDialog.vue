<template>
<div>
    <v-dialog
        v-model="data.dialog_edit"
        max-width="500px"
        persistent
    >
        <template v-slot:activator="{ on, attrs }">
            <v-form>
                <v-text-field
                    v-model="dialog.prop_time"
                    :label="$props.label_name"
                    readonly
                    clearable
                    :rules="timeRules"
                    v-bind="attrs"
                    v-on="on"
                ></v-text-field>
            </v-form>
        </template>
        <VueCtkDateTimePicker
            v-model="dialog.prop_time"
            :inline="true"
            format="YYYY-MM-DD HH:mm:ss"
            minuteInterval="5"
        ></VueCtkDateTimePicker>
        <v-container fluid>
            <v-row>
                <v-col cols="12" md="4" class="pa-0">
                    <v-btn
                        block
                        @click="cancel"
                    >cancel</v-btn>
                </v-col>

                <v-col cols="12" md="4" class="pa-0">
                    <v-btn
                        block
                        @click="restore"
                    >back</v-btn>
                </v-col>

                <v-col cols="12" md="4" class="pa-0">
                    <v-btn
                        block
                        @click="save(dialog.prop_time)"
                    >保存</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-dialog>
</div>
</template>

<script>
import { defineComponent, reactive, toRef, watch } from '@vue/composition-api'

export default defineComponent({
    props: {
        label_name: {
            type: String
        },
        time_data: {
            type: String
        },
        difference: {
            type: Number
        }
    },
    setup(props, { emit }) {
        console.log(props.difference);
        const data = reactive({
            time: null,
            dialog_edit: false,
        })

        const dialog = reactive({
            prop_time: props.time_data,
            prop_difference: props.difference,
            // penを押した時のデフォルトの値
            default: {
                time: props.time_data,
            },
            // ピッカーで「OK」を押したときに反映
            edit: {
                tiem: null,
            }
        })

        const timeRules = reactive([
            v => !!v || '時間の入力は必須です',
            // dialog.prop_difference > 28800000 || '開始時間と終了時間が8時間以内になるよう入力してください。',
            // v => {
            //     if(v === 1) {
            //         v > 28800000 || '開始時間と終了時間が8時間以内になるよう入力してください。'
            //     } else if (v === -1) {}
            // }
        ])

        const save =()=> {
            data.dialog_edit = false
            emit('time_edited', dialog.prop_time)
        }

        const restore =()=> {
            emit('restore', dialog.default.time)
            dialog.prop_time = dialog.default.time
        }

        const cancel =()=> {
            data.dialog_edit = false
            emit('restore', dialog.default.time)
            dialog.prop_time = dialog.default.time
        }
        watch(props, ()=> {
            dialog.prop_time = props.time_data
            dialog.prop_difference = props.difference
            // console.log('時間を更新しました');
        })

        return {
            data,
            dialog,
            timeRules,
            save,
            restore,
            cancel,
        }
    },
})
</script>
