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
        Usr_UID: 0,
    }),

    init() {
        fetch(`${Config.BackendHost}/session/whoami`, {
            credentials: "include"
        })
            .then(res => {
                if (!res.ok) return; // backend ist nicht erreichbar

                res.json().then(data => {
                    this.StateObj.Usr_LoggedIn = data['loggedIn'];
                    if (data['loggedIn']) {
                        this.StateObj.Usr_UID = data['uid'];
                    }
                });
            })
    }
});
