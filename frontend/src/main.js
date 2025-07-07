import { createApp } from 'vue'
import App from './App.vue'
// import './assets/css/style.css'
import './assets/css/admin.css' // Import CSS admin
import router from './router/index.js'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
// import './assets/js/hoa.js' 
// import '../node_modules/bootstrap/dist/css/bootstrap-grid.min.css'
// import '../node_modules/bootstrap/dist/css/bootstrap-utilities.min.css'

const app = createApp(App);
app.use(router);
app.mount('#app');