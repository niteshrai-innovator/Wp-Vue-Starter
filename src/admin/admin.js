import Vue from 'vue'
import Vuex from 'vuex'
import Router from 'vue-router'
import store from '../store/index'
import App from './App.vue'

import Dashboard from './components/pages/Dashboard.vue'
import Events from './components/pages/Events.vue'
import Settings from './components/pages/Settings.vue'
import OtherSetup from './module/OtherSetup.vue'
import ProjectSetup from './module/ProjectSetup.vue'

import HeaderNavigation from './components/nav/NavBar.vue'
import TabNavigation from './components/tabs/Navigation.vue'
import GeneralTab from './components/tabs/GeneralTab.vue'
import AnotherTab from './components/tabs/AnotherTab.vue'
import VueRouter from 'vue-router'

Vue.use( Vuex )
Vue.use( Router )

const routes = [
    {
        path: '/', components: { default: Dashboard, header: HeaderNavigation },
    },
    {
        path: '/another', components: { default: AnotherTab, header: HeaderNavigation },
    },
    {
        path: '/events', components: { default: Events },
    },
    {
        path: '/others', components: { default: OtherSetup , tab:TabNavigation },
    },
    {
        path: '/settings', components: { default: ProjectSetup, tab:TabNavigation },
    },
    {
        path: '/project-settings', components: { default: ProjectSetup, tab:TabNavigation },
    },
]

const router = new VueRouter({
    routes,
})

new Vue({
    el: '#wkwp-erp-addon-admin-app',
    store,
    router,
    render: h => h( App )
})