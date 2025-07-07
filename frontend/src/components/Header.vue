<template>
  <header class="header" data-header>
    <div class="container">
      <div class="overlay" data-overlay></div>
      <a href="#" class="logo">
        <img src="../assets/images/logo_ngang.png" width="160" height="50" alt="Footcap logo">
      </a>
      <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
        <ion-icon name="menu-outline"></ion-icon>
      </button>
      <nav class="navbar" data-navbar>
        <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
          <ion-icon name="close-outline"></ion-icon>
        </button>
        <a href="#" class="logo">
          <img src="../assets/images/logo_ngang.png" width="190" height="50" alt="Footcap logo">
        </a>
        <ul class="navbar-list">
          <li class="navbar-item"><router-link to="/" class="navbar-link">Trang Chủ</router-link></li>
          <li class="navbar-item"><router-link to="/about" class="navbar-link">Giới Thiệu</router-link></li>
          <li class="navbar-item"><router-link to="/product" class="navbar-link">Sản Phẩm</router-link></li>
          <li class="navbar-item"><router-link to="/blog" class="navbar-link">Bài Viết</router-link></li>
          <li class="navbar-item"><router-link to="/contact" class="navbar-link">Liên Hệ</router-link></li>
        </ul>
        <ul class="nav-action-list">
          <li>
            <button class="nav-action-btn" @click="toggleSearchPopup">
              <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Search</span>
            </button>
          </li>
          <li>
            <router-link to="/login" class="nav-action-btn">
              <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Login / Register</span>
            </router-link>
          </li>
          <li>
            <router-link to="/favourite" class="nav-action-btn">
              <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Wishlist</span>
              <data class="nav-action-badge" value="5" aria-hidden="true">5</data>
            </router-link>
          </li>
          <li>
            <router-link to="/cart" class="nav-action-btn">
              <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
              <data class="nav-action-text" value="318.00">Basket: <strong>$318.00</strong></data>
              <data class="nav-action-badge" value="4" aria-hidden="true">4</data>
            </router-link>
          </li>
        </ul>
      </nav>

      <!-- Search Popup -->
      <div class="search-popup" :class="{ active: showSearchPopup }" @click.self="toggleSearchPopup">
        <div class="search-content">
          <button class="close-btn" @click="toggleSearchPopup">
            <ion-icon name="close-outline"></ion-icon>
          </button>
          <form class="search-form">
            <input type="text" placeholder="Tìm kiếm sản phẩm..." class="search-input">
            <button type="submit" class="search-submit">
              <ion-icon name="search-outline"></ion-icon>
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const showSearchPopup = ref(false)

const toggleSearchPopup = () => {
  showSearchPopup.value = !showSearchPopup.value
  document.body.classList.toggle('popup-open', showSearchPopup.value)
}

onMounted(() => {
  const overlay = document.querySelector("[data-overlay]")
  const navOpenBtn = document.querySelector("[data-nav-open-btn]")
  const navbar = document.querySelector("[data-navbar]")
  const navCloseBtn = document.querySelector("[data-nav-close-btn]")

  const navElems = [overlay, navOpenBtn, navCloseBtn]

  navElems.forEach((elem) => {
    if (elem) {
      elem.addEventListener("click", () => {
        navbar.classList.toggle("active")
        overlay.classList.toggle("active")
      })
    }
  })
})
</script>

<style scoped>
.search-popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

.search-popup.active {
  opacity: 1;
  visibility: visible;
}

.search-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 600px; /* Tăng kích thước popup */
  position: relative;
  transform: scale(0.8);
  transition: transform 0.3s ease;
}

.search-popup.active .search-content {
  transform: scale(1);
}

.close-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
}

.search-form {
  display: flex;
  gap: 15px;
}

.search-input {
  flex: 1;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 18px;
}

.search-submit {
  background: #333;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
}

.search-submit ion-icon {
  font-size: 22px;
}

.popup-open {
  overflow: hidden;
}

/* Làm mờ các phần tử ngoài popup */
body.popup-open > *:not(.search-popup) {
  filter: blur(4px);
}
</style>