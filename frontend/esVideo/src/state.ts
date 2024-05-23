import { computed, reactive } from "vue";

// -----------------------------------------------------------------------------
// state.ts
// --------
// Haupt-Applikations-Zustandsspeicher
// -----------------------------------------------------------------------------

export const AppState = reactive({
    StateObj: {
        // User state
        // ----------
        Usr_LoggedIn: false,
    },

    save() {
        localStorage.setItem("State", JSON.stringify(this.StateObj));
    }
});
