<template>
  <div class="navigation" :class="{ active: isActive }">
    <ul>
      <li>
  <router-link to="/admin">
    <span class="icon">
      <img src="/src/assets/images/lgo.png" alt="F4 Store Shoes" style="width: 24px; height: 24px;">
    </span>
    <span class="title">F4 Store Shoes</span>
  </router-link>
</li>

      <li>
        <router-link to="/admin" active-class="hovered">
          <span class="icon">
            <ion-icon name="brand-apple-outline"></ion-icon>
          </span>
          <span class="title">Dashboard</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/customers" active-class="hovered">
          <span class="icon">
            <ion-icon name="people-outline"></ion-icon>
          </span>
          <span class="title">Khách Hàng</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/categories" active-class="hovered">
          <span class="icon">
            <ion-icon name="grid-outline"></ion-icon>
          </span>
          <span class="title">Danh Mục</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/products" active-class="hovered">
          <span class="icon">
            <ion-icon name="bag-outline"></ion-icon>
          </span>
          <span class="title">Sản Phẩm</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/vouchers" active-class="hovered">
          <span class="icon">
            <ion-icon name="gift-outline"></ion-icon>
          </span>
          <span class="title">Mã Giảm Giá</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/comments" active-class="hovered">
          <span class="icon">
            <ion-icon name="chatbubbles-outline"></ion-icon>
          </span>
          <span class="title">Bình Luận</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/orders" active-class="hovered">
          <span class="icon">
            <ion-icon name="cube-outline"></ion-icon>
          </span>
          <span class="title">Đơn Hàng</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/posts" active-class="hovered">
          <span class="icon">
            <ion-icon name="newspaper-outline"></ion-icon>
          </span>
          <span class="title">Bài Viết</span>
        </router-link>
      </li>

      <li>
        <router-link to="/admin/settings" active-class="hovered">
          <span class="icon">
            <ion-icon name="settings-outline"></ion-icon>
          </span>
          <span class="title">Settings</span>
        </router-link>
      </li>

      <li>
        <a href="#" @click="logout">
          <span class="icon">
            <ion-icon name="log-out-outline"></ion-icon>
          </span>
          <span class="title">Đăng Xuất</span>
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  isActive: {
    type: Boolean,
    default: false
  }
})

const logout = () => {
  // Logic đăng xuất
  console.log('Logout clicked')
  // Redirect về trang login
  // this.$router.push('/login')
}
</script>

<!-- <style scoped>
.navigation {
  position: fixed;
  width: 300px;
  height: 100%;
  background: #287bff;
  border-left: 10px solid #287bff;
  transition: 0.5s;
  overflow: hidden;
}

.navigation.active {
  width: 80px;
}

.navigation ul {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  padding-left: 5px;
  padding-top: 40px;
}

.navigation ul li {
  position: relative;
  width: 100%;
  list-style: none;
  border-top-left-radius: 30px;
  border-bottom-left-radius: 30px;
}

.navigation ul li:hover,
.navigation ul li.hovered {
  background-color: #fff;
}

.navigation ul li:nth-child(1) {
  margin-bottom: 40px;
  pointer-events: none;
}

.navigation ul li a {
  position: relative;
  display: block;
  width: 100%;
  display: flex;
  text-decoration: none;
  color: #fff;
}

.navigation ul li:hover a,
.navigation ul li.hovered a {
  color: #287bff;
}

.navigation ul li a .icon {
  position: relative;
  display: block;
  min-width: 60px;
  height: 60px;
  line-height: 75px;
  text-align: center;
}

.navigation ul li a .icon ion-icon {
  font-size: 1.75rem;
}

.navigation ul li a .title {
  position: relative;
  display: block;
  padding: 0 10px;
  height: 60px;
  line-height: 60px;
  text-align: start;
  white-space: nowrap;
}

/* Curve outside */
.navigation ul li:hover::before,
.navigation ul li.hovered::before {
  content: '';
  position: absolute;
  right: 0;
  top: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px 35px 0 10px #fff;
  pointer-events: none;
}

.navigation ul li:hover::after,
.navigation ul li.hovered::after {
  content: '';
  position: absolute;
  right: 0;
  bottom: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px -35px 0 10px #fff;
  pointer-events: none;
}
</style> -->