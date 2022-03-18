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
                    readonly
                    clearable

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
    props: {},
    setup(props, { emit }) {
        const data = reactive({
            time: null,
            dialog_edit: false,
        })

        const dialog = reactive({
            prop_time: null,
            // penを押した時のデフォルトの値
            default: {
                time: null,
            },
            // ピッカーで「OK」を押したときに反映
            edit: {
                tiem: null,
            }
        })

        const timeRules = reactive([
            v => !!v || '時間の入力は必須です',
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
            dialog.prop_time = null
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
