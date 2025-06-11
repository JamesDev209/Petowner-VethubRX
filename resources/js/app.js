// resources/js/app.js
import './bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import VueTheMask from 'vue-the-mask'
import '../css/style.css'

import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'


// Importa tus vistas
import Register from './Pages/Auth/Register.vue'
import VerifyAccount from './Pages/Auth/VerifyAccount.vue'
import Dashboard from './Pages/Dashboard.vue'
import SetPassword from './Pages/Auth/SetPassword.vue'
import App from '../components/App.vue'

// Configura Axios
window.axios = axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Define rutas
const routes = [
  { path: '/register', component: Register },
  { path: '/verify-account', component: VerifyAccount },
  { path: '/set-password', component: SetPassword },
  { path: '/dashboard', component: Dashboard },
  // …otras rutas
]

// Crea el router
const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Monta la aplicación
createApp(App)
  .use(router)
  .use(VueTheMask)
  .mount('#app')
