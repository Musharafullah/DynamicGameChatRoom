import Login from '../components/Login.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
    },
]

export default createRouter({
    history: createWebHistory(),
    routes
})
