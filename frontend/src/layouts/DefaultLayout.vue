<template>
  <div id="app">
    <Header/>
    <router-view />
    <Footer/>

    <a href="#top" class="go-top-btn" data-go-top>
      <ion-icon name="arrow-up-outline"></ion-icon>
    </a>
  </div>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
</script>