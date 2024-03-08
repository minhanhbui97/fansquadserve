import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';

import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia'
import Vueform from '@vueform/vueform'
import vueformConfig from '../../vueform.config'

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);
app.use(Vueform, vueformConfig)

app.mount('#app');
