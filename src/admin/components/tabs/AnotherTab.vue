<template>
    <div class="tab-container">
        <router-view name="header"></router-view>
        <h2>Another Tab</h2>
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'AnotherTab',

    data() {
        return {
            message: 'This is another tab'
        }    
    }
}
</script>

<style>

</style>