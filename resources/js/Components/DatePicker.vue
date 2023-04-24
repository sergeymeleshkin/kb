<template>
    <div class="date_picker">
        <template v-for="(month, index) in dates" :key="index">
            <div><b>{{ months[index] }}, {{ dates[index][0].getFullYear() }}</b></div>
            <div class="dates">
                <template v-for="(date, i) in dates[index]" :key="i">
                    <button @click="$emit('activeDate', date); activeDate = date" :class="{'active': activeDate == date }" class="date" :disabled="activeDate == date">
                        <div class='flex items-center px-4 py-4'>
                            <div class='text-center'>
                                <p> {{ days[date.getDay()] }} </p>
                                <p> {{ date.getDate() }} </p>
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
        function getDatesInRange(startDate, endDate) {
            const date = new Date(startDate.getTime());
            const dates = []
            let result = {}
            while (date < endDate) {
                dates.push(new Date(date))
                date.setDate(date.getDate() + 1)
            }
            dates.map(x => {
                let month = x.getUTCMonth()
                if(result[month] === undefined){
                    result[month] = []
                }
                result[month].push(x)
            });
            return result
        }
        const days = ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
        const months = ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь']
        const date = new Date()
        const endDate = new Date().setDate(date.getDate() + 14)
        const dates = getDatesInRange(date, endDate)
        let activeDate = ref(dates[Object.keys(dates)[0]][0])
        return {
            days,
            months,
            dates,
            activeDate
        }
    },
}
</script>
