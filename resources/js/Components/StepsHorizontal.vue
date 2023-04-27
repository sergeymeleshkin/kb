<template>
    <div class="steps_horizontal">
        <div class="col">
            <div class="num">Шаг {{ num }}</div>
        </div>
        <div class="col">
            <div class="title">{{ title }}</div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        num: {
            type: Number,
            default: 1,
        },
        title: {
            type: String,
            default: null,
        },
    },
}
</script>
