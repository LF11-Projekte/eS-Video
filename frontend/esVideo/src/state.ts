import { computed, reactive } from "vue";
import {Config} from "@/config";

// -----------------------------------------------------------------------------
// state.ts
// --------
// Haupt-Applikations-Zustandsspeicher
// -----------------------------------------------------------------------------

export const AppState = reactive({
    StateObj: reactive({
        // User state
        // ----------
        Usr_LoggedIn: false,
        Usr_UID: "",
    }),

    async init() {
        await fetch(`${Config.BackendHost}/session/whoami`, {
            credentials: "include"
        })
            .then(async res => {
                if (!res.ok) return; // backend ist nicht erreichbar

                await res.json().then(data => {
                    this.StateObj.Usr_LoggedIn = data['loggedIn'];
                    if (data['loggedIn']) {
                        this.StateObj.Usr_UID = data['uid'];
                    }
                });
            })
    }
});
