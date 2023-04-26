<template>
    <div class="date_picker">
        <template v-for="(month, index) in rangeDates" :key="index">
            <div>{{ month.monthStr }}</div>
            <div class="dates">
                <template v-for="(date, i) in rangeDates[index].dates" :key="i">
                    <button
                        @click="$emit('activeDate', date[0]); activeDate = date[0]"
                        :class="{'active': activeDate === date[0] }"
                        class="date" :disabled="activeDate === date[0]">
                        <div class='flex items-center px-4 py-4'>
                            <div class='text-center'>
                                <p> {{ date[1] }} </p>
                                <p> {{ date[2] }} </p>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
        </template>
    </div>
</template>
<script>
import {ref} from "vue";
export default {
    emits: ['activeDate'],
    setup() {
        function getDatesInRange(limit = 7) {
            const date = new Date()
            const endDate = new Date().setDate(
                date.getDate() + limit
            )
            const dates = {}
            const days = ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
            const months = ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь']
            while (date < endDate ) {
                const day = date.getDay()
                const month = date.getUTCMonth()
                const dateStr = date.toLocaleDateString()
                const dayStr = days[day]
                const monthStr = months[month]
                if(dates[month] === undefined){
                    dates[month] = {
                        monthStr,
                        "dates": []
                    }
                }
                dates[month]['dates'].push([
                    dateStr,
                    dayStr,
                    date.getDate()
                ])
                date.setDate(date.getDate() + 1)
            }
            return dates
        }
        return {
            rangeDates: getDatesInRange(14),
            activeDate: ref(new Date().toLocaleDateString())
        }
    },
}
</script>
