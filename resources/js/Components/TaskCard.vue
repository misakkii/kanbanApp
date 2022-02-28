<template>
<v-card>
    <v-sheet min-height="96">
        <v-col cols="12">
            <v-card v-for="n in 5" :key="n" class="ma-1">
                <v-card-text class="pa-1">
                    <div class="flex justify-between">
                        <div>プロジェクト名</div>
                        <div>00h00m</div>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <p class="text-h5 text--primary ma-0">タスク名</p>

                    <v-spacer></v-spacer>

                    <v-btn icon @click="show = !show">
                        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                    </v-btn>
                </v-card-actions>
                <v-expand-transition>
                    <div v-show="show">
                        <v-divider></v-divider>
                        <v-card-text>
                            ここに変更したい情報を入れる
                        </v-card-text>
                    </div>
                </v-expand-transition>
            </v-card>
        </v-col>
    </v-sheet>
</v-card>
</template>

<script>
import { computed, defineComponent, reactive } from '@vue/composition-api'
import useStore from '@/store/index'

//taskのカードのデフォルト

export default defineComponent({
    setup() {
        const store = useStore();

        const data = reactive({
            tasks: computed({
                get: ()=> store.getters[''],
                set: (val)=> store.commit('')
            })
        })

        return {
            data
        }
    },
})
</script>
