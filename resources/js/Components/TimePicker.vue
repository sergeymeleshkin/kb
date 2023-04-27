<template>
    <div v-if="times">
        <div class="time_picker">
            <template v-for="(time, index) in times" :key="index">
                <button  @click="$emit('timepicker', time); activeTime = time"
                         :class="{'active': activeTime === time}"
                         class="time">
                    {{ time }}
                </button>
            </template>
        </div>
    </div>
</template>
<script>
import {ref, watch} from "vue";
export default {
    props: {
        range: {
            type: String,
            default: null,
        },
        disable: {
            type: Array,
            default: [],
        },
    },
    emits: ['timepicker'],
    setup(props) {
        const generateHoursInterval = (
            startHourInMinute,
            endHourInMinute,
            interval,
        ) => {
            const times = []
            for (let i = 0; startHourInMinute < 24 * 60; i++) {
                if (startHourInMinute > endHourInMinute) break
                const hh = Math.floor(startHourInMinute / 60)
                const mm = startHourInMinute % 60
                times[i] = ('0' + (hh % 24)).slice(-2) + ':' + ('0' + mm).slice(-2)
                startHourInMinute = startHourInMinute + interval
            }
            return times;
        }
        let times = ref([])
        watch(
            () => props.disable,
            () => {
                const interval = 30
                const startDate = 60 * props.range.slice(0,2)
                const endDate = 60 * props.range.slice(6,-3)
                times.value = generateHoursInterval(startDate, endDate, interval)
                props.disable.map((x) => {
                    const xt = String(x.slice(11,-3))
                    const index = times.value.indexOf(xt)
                    if (index > -1) {
                        times.value.splice(index, 1)
                    }
                })
            },
            { deep: true }
        )
        return {
            activeTime: ref(''),
            times
        }
    },
}
</script>
