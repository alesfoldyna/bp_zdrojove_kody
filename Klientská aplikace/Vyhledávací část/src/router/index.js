import Vue from 'vue'
import Router from 'vue-router'

import MainSearch from '@/components/MainSearch'
import About from '@/components/About'
import SearchResult from '@/components/SearchResulte'

Vue.use(Router)


export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'mainSearch',
      component: MainSearch
    },
    {
      path: '/about',
      name: 'about',
      component: About
    },
    {
      path: '/search',
      name: 'search',
      component: SearchResult
    }
  ]
})
