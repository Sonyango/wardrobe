import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import App from './App.vue'
import router from './router'

const pinia = createPinia();

const app = createApp(App);

// createApp(App)
//     .use(router)
//     .mount('#app')

app.use(pinia);
app.use(router);

app.mount('#app');
