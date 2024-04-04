import './bootstrap';
import '../css/app.css';
import 'primevue/resources/themes/aura-light-amber/theme.css';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import { createApp } from 'vue';

import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import Vueform from '@vueform/vueform';
import vueformConfig from '../../vueform.config';
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faCopy } from '@fortawesome/free-regular-svg-icons'
import { faCopy as faCopySolid, faSpinner, faFileLines, faChartSimple, faUsers, faHome, faPhone, faUser } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faCopy, faCopySolid, faSpinner, faFileLines, faChartSimple, faUsers, faHome, faPhone, faUser)

const app = createApp(App);
const pinia = createPinia();

app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('font-awesome-icon', FontAwesomeIcon)

app.use(router);
app.use(pinia);
app.use(Vueform, vueformConfig);
app.use(PrimeVue, { ripple: true });

const toastOptons = {
  position: 'top-right',
  timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: false,
  closeButton: 'button',
  icon: true,
  rtl: false,
  maxToasts: 5,
};

app.use(Toast, toastOptons);

app.mount('#app');
