import { Api } from '@/use/api'
export const mainContentModule = {
    namespaced: true,
    state: {
        mainContent: {},
    },
    mutations: {
        SET_MAIN_CONTENT ( state, playload ) {
            if ( playload != 'undefined' )
            {
                state.mainContent = playload
            }
        },
    },
    actions: {
        fetchMainContent ( { commit, state }, playload ) {
            state.request && state.request.cancel()
            Api.post( 'content', playload )
                .then( response => {
                    commit( 'SET_MAIN_CONTENT', response )
                } )
        },
    }
}
