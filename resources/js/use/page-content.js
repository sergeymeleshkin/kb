import { computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
export function usePageContent () {
    const store = useStore()
    const route = useRoute()
    function getContent () {
        store.commit( 'pageContentModule/SET_PAGE_CONTENT_LOADING', true )
        store.dispatch( 'pageContentModule/fetchPageContent', {
            'route': route.name, ...route.query,
            'routeParams': route.params
        } )
    }
    function resetContent () {
        store.commit( 'pageContentModule/SET_PAGE_CONTENT_LOADING', false )
        store.commit( 'pageContentModule/SET_PAGE_CONTENT', {} )
    }
    const loading = computed( () => store.state.pageContentModule.loading )
    const content = computed( () => store.state.pageContentModule.content )
    const contentIsEmpty = computed( () => store.state.pageContentModule.contentIsEmpty )

    onMounted( () => content.value.data ?? getContent() )
    watch(
        () => route.name,
        ( now, old ) => ( old != now ) && resetContent(),
        { deep: true }
    )
    watch(
        () => route.params,
        ( cur ) => cur && getContent(),
        { deep: true }
    )
    return {
        loading,
        content,
        contentIsEmpty
    }
}
