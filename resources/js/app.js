import './bootstrap';
import '../css/app.css';
import 'primevue/resources/themes/aura-light-amber/theme.css';


import { createApp } from 'vue';

import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import Vueform from '@vueform/vueform';
import vueformConfig from '../../vueform.config';
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

const app = createApp(App);
const pinia = createPinia();

app.component('DataTable', DataTable);
app.component('Column', Column);

app.use(router);
app.use(pinia);
app.use(Vueform, vueformConfig);
app.use(PrimeVue, { ripple: true });

app.mount('#app');
