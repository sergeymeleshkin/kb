import createPersistedState from "vuex-persistedstate"
import { createStore } from 'vuex'
import { pageContentModule } from '@/store/modules/page-content.module'
import { mainContentModule } from '@/store/modules/main-content.module'
const dataState = createPersistedState( {
    paths: [ 'localeModule', 'pageContentModule' ]
} )
export const store = createStore( {
    modules: {
        pageContentModule,
        mainContentModule
    },
    plugins: [ dataState ]
} );
