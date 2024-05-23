<template>
  <div class="login-view">
    <div class="login-popup">
      <h1>Anmelden</h1>
      <form @submit.prevent="doLogin()">
        <input type="text" placeholder="Anmeldename" v-model="loginName">
        <input type="password" placeholder="Kennwort" v-model="password">

        <p class="error" v-if="errorMsg">
          {{errorMsg}}
        </p>

        <button>Anmelden</button>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {state} from "vue-tsc/out/shared";
import {AppState} from "@/state";
import router from "@/router";

export default defineComponent({
  name: "LoginView",
  data() {
    return {
      loginName: "",
      password: "",
      errorMsg: "",
    }
  },
  mounted() {
    // wenn schon angemeldet -> weiterleitung auf /home
    if (AppState.StateObj.Usr_LoggedIn) {
      router.push({path: "home"});
    }
  },
  methods: {
    doLogin() {
      // sicherstellen, dass name und pwd nicht leer sind
      if (!this.loginName) return;
      if (!this.password) return;

      // Loginanfrage an das Backend
      fetch("http://10.100.0.170/session/login", {
        method: "POST",
        body: JSON.stringify({
          login: this.loginName,
          password: this.password,
        })
      }).then(value => {

        // Fehler bei der Anmeldung
        if (!value.ok) {
          this.errorMsg = "Anmeldung fehlgeschlagen";
          return;
        }

        // Login-Flag setzen
        AppState.StateObj.Usr_LoggedIn = true;
        AppState.save();

        // redirect auf die homepage
        router.push({path: "home"});
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

.login-popup form {
  display: flex;
  flex-direction: column;
}

.login-popup form input {
  margin: 0.5em 1em;
  padding: 0.5em;

  color: white;
  background: #535353;

  border: none;
  border-radius: 0.2em;
  outline: none;

  text-align: center;
}

.login-popup form button {
  margin: 1em;
  padding: 0.5em;

  color: white;
  background: #4390a8;

  border: none;
  border-radius: 0.2em;

  text-align: center;
}

.error {
  width: 100%;

  color: #f15b5b;
  text-align: center;
}
</style>
