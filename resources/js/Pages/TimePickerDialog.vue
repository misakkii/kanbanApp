<template>
<div>
    <v-dialog
        v-model="dialog_edit"
        max-width="500px"
        persistent
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="dialog.prop_time"
                :label="prop_label_name"
                readonly
                clearable
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
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
                    >諦める</v-btn>
                </v-col>

                <v-col cols="12" md="4" class="pa-0">
                    <v-btn
                        block
                        @click="restore"
                    >I'll be back</v-btn>
                </v-col>

                <v-col cols="12" md="4" class="pa-0">
                    <v-btn
                        block
                        @click="save(time)"
                    >保存してみる</v-btn>
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
        }
    },
    setup(props, { emit }) {
        const data = reactive({
            time: null,
            dialog_edit: false,
        })

        const dialog = reactive({
            prop_label_name: props.label_name,
            prop_time: props.time_data,
            // penを押した時のデフォルトの値
            default: {
                time: props.time_data,
            },
            // ピッカーで「OK」を押したときに反映
            edit: {
                tiem: null,
            }
        })
        const time = toRef(dialog, 'prop_time')
        const dialog_edit = toRef(data, 'dialog_edit')
        const prop_label_name = toRef(dialog, 'prop_label_name')

        const save =()=> {
            data.dialog_edit = false
            emit('time_edited', time.value)
        }

        const restore =()=> {
            emit('restore', dialog.default.time)
            dialog.prop_time = dialog.default.time
        }

        const cancel =()=> {
            data.dialog_edit = false
            emit('restore', dialog.default.time)
            dialog.prop_time = dialog.default.time
            // console.log(time.value);
        }
        watch(props, ()=> {
            dialog.prop_time = props.time_data
            // console.log('時間を更新しました');
        })

        return {
            time,
            prop_label_name,
            dialog_edit,
            dialog,
            save,
            restore,
            cancel,
        }
    },
})
</script>
