import { Api } from '@/use/api'
export const pageContentModule = {
    namespaced: true,
    state: {
        content: {},
        loading: true,
        request: null,
    },
    mutations: {
        SET_PAGE_CONTENT ( state, playload ) {
            if ( playload.data != 'undefined' )
            {
                state.content = playload
            }
        },
        SET_PAGE_CONTENT_LOADING ( state, playload ) {
            state.loading = playload
        },
    },
    actions: {
        fetchPageContent ( { commit, state }, playload ) {
            state.request && state.request.cancel()
            Api.post( 'content', playload )
                .then( response => {
                    if(!response.exception){
                        commit( 'SET_PAGE_CONTENT_LOADING', false )
                        commit( 'SET_PAGE_CONTENT', response )
                    }
                } )
        },
    }
}
