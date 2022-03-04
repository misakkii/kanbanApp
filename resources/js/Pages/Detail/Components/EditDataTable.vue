<template>
<v-data-table
    hide-default-footer
    :headers="headers"
    :items="items"
>
    <template slot="items">
        <td>{{  }}</td>
        <td>{{  }}</td>
        <td>{{  }}
            <v-icon
                small
                @click="editItem(item)"
            ></v-icon>
        </td>
    </template>
</v-data-table>
</template>

<script>
import { defineComponent } from '@vue/composition-api'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    data() {
        return {
            form: this.$inertia.form({
                executed_time: null,
                suspended_time: null,
            })
        }
    },
    props: {
        work_time: {
            type: Array
        }
    },
    setup(props) {
        // console.log(props.work_time);
        const update =()=> {
            // form.data.put(`/detail/${}`)
        }

        return {
            update,
            headers:[
                {text: '実行日', value: 'attend_date'},
                {text: '実行時間', value: 'total_time'},
            ],
            items:[
                {
                    attend_date: '2022-02-20',
                    total_time: '0h30m'
                },
                {
                    attend_date: '2022-02-21',
                    total_time: '0h39m'
                },
                {
                    attend_date: '2022-02-21',
                    total_time: '0h25m'
                },
            ]
        }
    },
})
</script>
