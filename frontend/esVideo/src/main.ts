import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import {AppState} from "@/state";

console.log(localStorage.getItem("State"));

// local state storage anlegen
if (!localStorage.getItem("State")) {

    // neuen AppState anlegen und ablegen
    localStorage.setItem("State", JSON.stringify(AppState.StateObj));
}
else {
    // vorherigen AppState laden
    AppState.StateObj = JSON.parse(localStorage.getItem("State")!);
}

const app = createApp(App)

app.use(router)
app.mount('#app')
