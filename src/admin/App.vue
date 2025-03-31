<template>
    <div id="vue-backend-app">
        <br>
        <router-view name="header"></router-view>
        <router-view name="tab"></router-view>

        <router-view></router-view>
    </div>
</template>

<script>
import HeaderNavigation from './components/nav/NavBar.vue'
export default {
    name: 'App',
    components: {
        'nav-navigation': HeaderNavigation,
    }
}
</script>

<style>

</style>