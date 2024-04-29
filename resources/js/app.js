import { createApp } from "vue";
import router from './router'
import axios from "axios";
import './bootstrap.js';
import AppComponent from './components/App.vue';



axios.defaults.headers.common['Accept'] = 'application/json';

createApp({
    components: {
        AppComponent
    }
}).use(router).mount('#app')
