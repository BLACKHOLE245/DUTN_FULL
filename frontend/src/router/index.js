import { createRouter, createWebHistory } from 'vue-router'

// Import layouts
import DefaultLayout from '../layouts/DefaultLayout.vue'
import AdminLayout from '../layouts/AdminLayout.vue'

// Import frontend pages
import HomePage from '../pages/HomePage.vue'
import AboutPage from '../pages/AboutPage.vue'
import BlogPage from '../pages/BlogPage.vue'
import ContactPage from '../pages/ContactPage.vue'
import CartPage from '../pages/CartPage.vue'
import DetailPage from '../pages/DetailPage.vue'
import FavouritePage from '../pages/FavouritePage.vue'
import LoginPage from '../pages/LoginPage.vue'
import ProductPage from '../pages/ProductPage.vue'
import PayPage from '../pages/PayPage.vue'

// Import admin pages
import AdminDashboard from '../pages/admin/AdminDashboard.vue'
import AdminCustomers from '../pages/admin/AdminCustomers.vue'
import AdminCategories from '../pages/admin/AdminCategories.vue'
import AdminProducts from '../pages/admin/AdminProducts.vue'
import AdminVouchers from '../pages/admin/AdminVouchers.vue'
import AdminComments from '../pages/admin/AdminComments.vue'
// import AdminOrders from '../pages/admin/AdminOrders.vue'
// import AdminPosts from '../pages/admin/AdminPosts.vue'
// import AdminSettings from '../pages/admin/AdminSettings.vue'

const routes = [
  // Frontend routes với DefaultLayout
  {
    path: '/',
    component: DefaultLayout,
    children: [
      { path: '', name: 'home', component: HomePage },
      { path: 'about', name: 'about', component: AboutPage },
      { path: 'blog', name: 'blog', component: BlogPage },
      { path: 'contact', name: 'contact', component: ContactPage },
      { path: 'cart', name: 'cart', component: CartPage },
      { path: 'detail/:id', name: 'detail', component: DetailPage },
      { path: 'favourite', name: 'favourite', component: FavouritePage },
      { path: 'login', name: 'login', component: LoginPage },
      { path: 'product', name: 'product', component: ProductPage },
      { path: 'pay', name: 'pay', component: PayPage },
    ]
  },
  // Admin routes với AdminLayout
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      { path: '', name: 'admin-dashboard', component: AdminDashboard },
      { path: 'customers', name: 'admin-customers', component: AdminCustomers },
      { path: 'categories', name: 'admin-categories', component: AdminCategories },
      { path: 'products', name: 'admin-products', component: AdminProducts },
      { path: 'vouchers', name: 'admin-vouchers', component: AdminVouchers },
      { path: 'comments', name: 'admin-comments', component: AdminComments },
      // { path: 'orders', name: 'admin-orders', component: AdminOrders },
      // { path: 'posts', name: 'admin-posts', component: AdminPosts },
      // { path: 'settings', name: 'admin-settings', component: AdminSettings },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router