<template>
  <div class="admin-container" :class="{ active: sidebarActive }">
    <AdminSidebar :is-active="sidebarActive" />
    <div class="main">
      <AdminHeader @toggle-sidebar="toggleSidebar" />
      <div class="admin-content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminHeader from '../components/admin/AdminHeader.vue'
import AdminSidebar from '../components/admin/AdminSidebar.vue'

const sidebarActive = ref(false)

const toggleSidebar = () => {
  sidebarActive.value = !sidebarActive.value
}
</script>

<!-- <style scoped>
.admin-container {
  position: relative;
  width: 100%;
  min-height: 100vh;
  background: #f5f5f5;
}

.main {
  position: absolute;
  width: calc(100% - 300px);
  left: 300px;
  min-height: 100vh;
  background: #fff;
  transition: 0.5s;
}

.admin-container.active .main {
  width: calc(100% - 80px);
  left: 80px;
}

.admin-content {
  padding: 20px;
  background: #f5f5f5;
  min-height: calc(100vh - 60px);
}

/* Responsive */
@media (max-width: 991px) {
  .admin-container .main {
    width: 100%;
    left: 0;
  }
  
  .admin-container.active .main {
    width: calc(100% - 80px);
    left: 80px;
  }
}
</style> -->