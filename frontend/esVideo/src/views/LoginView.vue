<template>
  <div class="login-view">
    <div class="login-popup">
      <h1>Willkommen</h1>
      <p>Im eS-Videoportal des <b>BSZET Dresden</b>.</p>
      <p class="info">Bitte verwenden Sie Ihre Schulanmeldung und Authentifizieren Sie sich via des Schulkonto-Authentifikator.</p>
      <div class="center">
        <button @click="doLogin()" v-if="loginWindow == null">
          <div class="left">
            SKA
          </div>
          <div class="right">
            Authentifizieren
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {state} from "vue-tsc/out/shared";
import {AppState} from "@/state";
import router from "@/router";
import {Config} from "@/config";

export default defineComponent({
  name: "LoginView",
  data() {
    return {
      loginWindow: (null as Window|null),
    }
  },
  methods: {
    async doLogin() {
      // Es ist bereits ein Login-Fenster geöffnet
      if (this.loginWindow != null) return;

      // Login-Attempt token vom Backend einholen
      let atp = await new Promise((resolve) => fetch(`${Config.BackendHost}/session/attempt`, {
        credentials: 'include',
      }).then(res => {
        res.json().then(data => {
          resolve(data['atp']);
        })
      }));

      const redirectLink = `${Config.AppHost}/login/${atp}/confirm`;
      this.loginWindow = window.open(`${Config.SKAHost}/auth/login-form?r=${encodeURIComponent(redirectLink)}`, 'Login', 'width=800,height=600,status=0,toolbar=0');

      // Auf Schließen des Fensters warten
      let timer = setInterval(() => {
        try {
          if (this.loginWindow!.closed ?? false) {
            // Timer stoppen und Fenster für möglichen nächsten Aufruf löschen
            clearInterval(timer);
            this.loginWindow = null;

            // Login prüfen
            this.confirmLogin();
          }
        } catch {
        } // idc
      }, 1000);
    },
    confirmLogin() {
      // Existiert jetzt eine Sitzung?
      fetch(`${Config.BackendHost}/session/whoami`, {
        credentials: "include"
      })
          .then(res => {
            if (!res.ok) return; // backend ist nicht erreichbar

            res.json().then(async data => {
              if (data['loggedIn']) {
                // Wenn ja -> Sitzung laden und Redirect zu /home
                await AppState.init();
                this.$router.push({path: "/home"});
              }
            });
          })
    }
  }
})
</script>

<style scoped>
.login-view {
  display: flex;
  justify-content: center;
  align-items: center;

  width: 100%;
  height: 100%;

  background: linear-gradient(322deg, rgb(145, 122, 212) 0%, rgba(54,191,167,1) 100%);
}

.login-popup {
  width: 25em;

  color: white;
  background: #2F2F2F;

  border-radius: 0.5em;
}

.login-popup h1 {
  width: 100%;
  text-align: center;
}

.login-popup p {
  width: 100%;
  text-align: center;
}

.login-popup .info {
  opacity: 0.4;
}

.login-popup .center {
  display: flex;
  justify-content: center;
  width: 100%;
}

.login-popup button {
  display: grid;
  grid-template-columns: auto 1fr;

  margin: 1em;
  padding: 0;

  color: white;
  background: transparent;

  border: none;
  cursor: pointer;
}

.login-popup div {
  padding: 0.5em;
}

.login-popup button .left {
  font-weight: 900;
  background-color: #1D7895;

  border-radius: 0.2em 0 0 0.2em;
}

.login-popup button .right {
  background: #4390a8;
  border-radius: 0 0.2em 0.2em 0;
}

.error {
  width: 100%;

  color: #f15b5b;
  text-align: center;
}
</style>
