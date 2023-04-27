<template>
    <PublicLayout>
        <template v-if="content.services.length !== 0">
            <h1>Запись на шиномонтаж</h1>
            <div :class="{'disabled': result}">
                <ul class="overflow-y-auto mx-auto">
                    <StepsHorizontal :num="1" :title="'Выбор места'" />
                    <template v-for="(item, index) in content.services" :key="index">
                        <li class="w-full min-w-full border my-3 rounded-lg">
                            <label :for="index" class="flex p-3 gap-3 leading-8 cursor-pointer">
                                <div class="flex items-center">
                                    <input type="radio"
                                           :id="index"
                                           @click="setService(index, item.id)"
                                           :checked="form.service_id === item.id">
                                </div>
                                <div class="grid">
                                    <span class="text-xl">{{ item.name }}</span>
                                    <span class="text-sm text-gray-800">{{ item.address }}</span>
                                </div>
                            </label>
                        </li>
                    </template>
                </ul>
                <div v-if="content.services"></div>
                <template v-if="form.service_id">
                    <StepsHorizontal :num="2" :title="'Выбор даты и времени оказания услуг'" />
                    <div class="datetime_container">
                        <DatePicker @datepicker="(date) => form.date = date" />
                        <TimePicker @timepicker="(time) => form.time = time" :range="schedule[0]" :disable="schedule[1]"  />
                    </div>
                </template>
                <template v-if="form.time">
                    <StepsHorizontal :num="3" :title="'Ввод контактов'" />
                    <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
                        <input type="text" v-model="form.name" placeholder="Введите имя" class="form-control">
                        <input type="tel" v-model="form.tel" placeholder="Введите Телефон" class="form-control">
                    </div>
                </template>
            </div>
            <div v-if="result" class="flex gap-3 py-4 my-3 justify-center bg-gray-50">
                <p><b>{{ result }}</b></p>
                <button @click="result = ''">[ Закрыть ]</button>
            </div>
            <div v-else-if="form.time" class="flex my-4 justify-center">
                <button @click="enroll()" class="amber_button text-xl uppercase" :disabled="!form.name || !form.tel">Записаться</button>
            </div>
        </template>
        <div v-else>
            Необходимо выполнить: ./artisan db:seed
        </div>
    </PublicLayout>
</template>
<script>
import PublicLayout from '@/Layouts/PublicLayout.vue'
import StepsHorizontal from '@/Components/StepsHorizontal.vue'
import DatePicker from "@/Components/DatePicker.vue"
import {usePageContent} from "@/use/page-content"
import {reactive, ref, watch} from "vue"
import { Api } from '@/use/api'
import TimePicker from "@/Components/TimePicker.vue";
export default {
    components: {
        PublicLayout,
        StepsHorizontal,
        TimePicker,
        DatePicker
    },
    setup() {
        let result = ref('')
        let schedule = ref({})
        let form = reactive({
            service_id: null,
            date: new Date().toDateString(),
            name: '',
            tel: ''
        });
        function setService(index, id){
            form.service_id = id
            getSchedule()
        }
        function enroll(){
            Api.post( 'montage/enroll', form )
                .then( response => {
                    getSchedule()
                    result.value = response
                } )
        }
        function getSchedule(){
            Api.post( 'montage/schedule', form )
                .then( res => {
                    const day = new Date(form.date).getUTCDay()
                    schedule.value = [
                        res['all'][day],
                        res['reserved']
                    ]
                })
        }
        watch(() => form.date,
            () => getSchedule(),
            { deep: true }
        )
        return {
            ...usePageContent(),
            setService,
            form,
            schedule,
            enroll,
            result
        }
    },
}
</script>
