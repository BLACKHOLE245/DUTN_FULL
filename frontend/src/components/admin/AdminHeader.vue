<template>
  
  <div class="topbar">
    <div class="toggle" @click="toggleSidebar">
      <ion-icon name="menu-outline"></ion-icon>
    </div>

    <div class="user">
      <img src="" alt="">
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['toggle-sidebar'])

const toggleSidebar = () => {
  emit('toggle-sidebar')
}
</script>

