<template>
    <PublicLayout>
       <h1>Запись на шиномонтаж</h1>
       <div :class="{'pointer-events-none cursor-default opacity-30': result}">
           <StepsHorizontal :num="1" :title="'Выбор места'" />
           <ul v-show="content.services" class="overflow-y-auto mx-auto">
               <template v-for="(item, index) in content.services" :key="index">
                   <li class="w-full min-w-full border my-3 rounded-lg">
                       <label :for="index" class="flex p-3 gap-3 leading-8 cursor-pointer">
                           <div class="flex items-center">
                               <input type="radio" :id="index" :checked="form.service_id === item.id" @click="setService(item.id)" class="h-8 w-8 rounded-full border-amber-400 text-amber-500 focus:ring-amber-500">
                           </div>
                           <div class="grid">
                               <span class="text-xl">{{ item.name }}</span>
                               <span class="text-sm text-gray-800">{{ item.address }}</span>
                           </div>
                       </label>
                   </li>
               </template>
           </ul>
           <template v-if="form.service_id">
               <StepsHorizontal :num="2" :title="'Выбор даты и времени оказания услуг'" />
               <div class="datetime_container">
                   <DatePicker @active-date="(date) => setActiveDate(date)" />
                   <div v-if="schedule.ranges">
                       <div class="time_picker">
                           <template v-for="(range, index) in schedule.ranges" :key="index">
                               <button @click="setActiveTime(range, index)" :class="[{'text-gray-300 bg-gray-100': range.reserve },{'active': form.activeTime === range.time}]" class="time" :disabled="range.reserve">
                                   {{ range.time }}
                               </button>
                           </template>
                       </div>
                   </div>
               </div>
           </template>
           <template v-if="form.activeTime">
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
        <div v-else-if="form.activeTime && form.name && form.tel" class="flex my-4 justify-center">
            <button @click="enroll()" class="amber_button text-xl uppercase">Записаться</button>
        </div>
    </PublicLayout>
</template>
<script>
import PublicLayout from '@/Layouts/PublicLayout.vue'
import StepsHorizontal from '@/Components/StepsHorizontal.vue'
import DatePicker from "@/Components/DatePicker.vue"
import {usePageContent} from "@/use/page-content"
import {reactive, ref} from "vue"
import { Api } from '@/use/api'
export default {
    components: {
        PublicLayout,
        StepsHorizontal,
        DatePicker
    },
    setup() {
        let result = ref('')
        function setService(id){
            form.service_id = id
            form.activeTime = null
            updateSchedule()
        }

        function addMinutes(date, minutes) {
            return new Date(date.getTime() + minutes*60000);
        }

        function getTime(date){
            return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
        }

        function setHours(time){
            return new Date(form.date.setHours(time[0], time[1],0,0))
        }

        function getDatesInRange(times) {
            const startTime = times[0].split(':')
            const endTime = times[1].split(':')
            const startDate = setHours(startTime)
            const endDate = setHours(endTime)
            const dates = [startDate]
            for (let i = 0; i < 24*2; i++) {
                let lastDate = dates[dates.length - 1]
                let nextDate = addMinutes(lastDate, 30)
                if(nextDate > endDate){
                    break
                }
                dates.push(nextDate)
            }
            return dates
        }

        function useReservedFilter(all, reserved) {
            let ranges = []
            all.map((date) => {
                const time = getTime(date)
                let reserve = false
                reserved.map((date2) => {
                    const time2 = getTime(new Date(date2))
                    if(time === time2){
                        reserve = true
                        return true;
                    }
                })
                ranges.push({date, time, reserve})
            })
            return ranges
        }
        function getSchedule(schedules) {
            const dayNum = new Date(form.date).getDay()
            const scheduleForDay = schedules['all'][dayNum].split('-')
            const rangeDates = getDatesInRange(scheduleForDay)
            return useReservedFilter(rangeDates, schedules['reserved'])
        }
        function updateSchedule(){
            Api.post( 'montage/schedule', form )
                .then( response => {
                    schedule.ranges = getSchedule(response)
                } )
        }
        function setActiveDate(date){
            form.activeTime = null
            form.date = date
            updateSchedule()
        }
        function setActiveTime(range, i){
            form.dateStr = setHours(range.time.split(':')).toLocaleString()
            form.activeTime = range.time
            form.name = 'Клиент на '+range.time
            form.tel = '+79001110022 (валидации нет, тест)'
        }
        function enroll(){
            Api.post( 'montage/enroll', form )
                .then( response => {
                    updateSchedule()
                    result.value = response
                } )
        }
        let schedule = reactive({})
        let form = reactive({
            service_id: null,
            date: new Date(),
            name: '',
            tel: ''
        });
        return {
            ...usePageContent(),
            setService,
            setActiveDate,
            setActiveTime,
            form,
            schedule,
            enroll,
            result
        }
    },
}
</script>
