import Login from '../components/Login.vue'
import Join from '../components/Join.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
    },
    {
        path: '/join-room/:code',
        name: 'Join',
        component: Join,
    },

]

export default createRouter({
    history: createWebHistory(),
    routes
})
