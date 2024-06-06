import { createApp } from "vue";
import router from './router'
import axios from "axios";
import './bootstrap.js';
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

import AppComponent from './components/App.vue';
import VueToast from "vue-toast-notification";

axios.defaults.headers.common['Accept'] = 'application/json';

createApp({
    components: {
        AppComponent
    }
}).use(router).use(VueToast).mount('#app')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '06309dd11eee2c1d9bd8', // Your Pusher app key
    cluster: 'ap2', // Your Pusher cluster
    encrypted: true, // Ensure that your connection is encrypted
});

