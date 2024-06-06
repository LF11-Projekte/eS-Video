import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import {AppState} from "@/state";

async function run() {
    // Sitzung wiederherstellen
    await AppState.init();

    const app = createApp(App)
    app.use(router)
    app.mount('#app')
}

run();