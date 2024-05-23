<template>
    <div v-if="!AppState.StateObj.Usr_LoggedIn">
      <router-link to="login" class="login-lnk">Anmelden</router-link>
    </div>
    <div v-else class="user-menu">
        <p>Schlesinger, Ewan</p>
        <div class="profile-picture"></div>
    </div>
</template>

<script lang="ts">

import {AppState} from "@/state";
import {defineComponent} from "vue";

export default defineComponent({
  computed: {
    AppState() {
      return AppState
    }
  }
})

</script>

<style scoped>
.user-menu {
    display: flex;
    align-items: center;
    margin-right: 0.5em;
}

p {
    color: white;

    font-size: 0.8em;
    font-family: inter;
}

.profile-picture {
    width: 2em;
    height: 2em;

    margin-left: 0.5em;

    background: white;
    border-radius: 100%;
}
.login-lnk {
  color: white;
  text-decoration: none;

  margin-right: 1em;
}
</style>