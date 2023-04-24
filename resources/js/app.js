import { createApp } from 'vue'
import router from './router'
import { store } from '@/store'
import App from './Layouts/App.vue'

const app = createApp( App )

app.use( router )
    .use( store )
    .mount( '#app' )
