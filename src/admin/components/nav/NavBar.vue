<template>
    <header class="header" id="header">
   <section class="nav-wrapper nav-container">
    <router-link to="/" class="brand">Wp Erp Module</router-link>
      <div class="burger" id="burger">
         <span class="burger-line"></span>
         <span class="burger-line"></span>
         <span class="burger-line"></span>
      </div>
      <span class="overlay"></span>
      <nav class="navbar" id="navbar">
         <ul class="menu" id="menu">
            <li class="menu-item"><router-link to="/">Dashboard</router-link></li>
            <li class="menu-item"><router-link to="/another">Others</router-link></li>
            <!-- <li class="menu-item menu-dropdown">
               <span class="menu-link" data-toggle="submenu">Feature<i class="dashicons dashicons-arrow-down-alt2"></i></span>
               <ul class="submenu">
                  <li class="submenu-item"><a href="#" class="submenu-link">Feature Link</a></li>
                  <li class="submenu-item"><a href="#" class="submenu-link">Feature Link</a></li>
                  <li class="submenu-item"><a href="#" class="submenu-link">Feature Link</a></li>
                  <li class="submenu-item"><a href="#" class="submenu-link">Feature Link</a></li>
               </ul>
            </li> -->
         </ul>
      </nav>
   </section>
</header>
</template>

<script>
export default {
    name: 'TabNavigation'
}
</script>

<style>

</style>