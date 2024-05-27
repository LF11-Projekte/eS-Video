<template>
  <transition name="fade" mode="out-in">
    <div v-if="!LoggedIn">
      <router-link to="/login" class="login-lnk">Anmelden</router-link>
    </div>
    <div v-else class="user-menu" @click="DropDownOpen = !DropDownOpen">
        <p>{{Username}}</p>
        <div class="profile-picture" :style='`background-image: url(${ProfilePic});`'></div>
    </div>
  </transition>
    <div class="drop-down" v-if="DropDownOpen">
      <div class="seperator"></div>
      <router-link :key="UID" :to="`/profile/${UID}`" @click="closeDropdown()">Mein Profil</router-link>
      <router-link to="/upload" @click="closeDropdown()">Video Hochladen</router-link>
      <router-link to="/edit-profile" @click="closeDropdown()">Einstellungen</router-link>
      <div class="seperator"></div>
      <button @click="doLogout()">Abmelden</button>
    </div>
</template>

<script lang="ts">

import {AppState} from "@/state";
import {defineComponent} from "vue";
import {Config} from "@/config";
import router from "@/router";

export default defineComponent({
  mounted() {
    this.fetchUserdata();
  },
  data() {
    return {
      Username: "",
      ProfilePic: "",

      DropDownOpen: false,
    }
  },
  computed: {
    Config() {
      return Config
    },
    UID() {
      return AppState.StateObj.Usr_UID
    },
    LoggedIn() {
      return AppState.StateObj.Usr_LoggedIn
    }
  },
  methods: {
    fetchUserdata() {
      fetch(`${Config.BackendHost}/user`, {
        credentials: "include"
      })
          .then(res => {
            if (!res.ok) return;

            res.json().then(obj => {
              this.Username = obj['displayName'];
              this.ProfilePic = obj['profilePicture'];
            })
          })
    },
    doLogout() {
      // Loginanfrage an das Backend
      fetch("http://10.100.0.170/session/logout", {
        method: "POST",
        credentials: "include",
      }).then(value => {
        // Login-Flag setzen
        AppState.StateObj.Usr_LoggedIn = false;
        AppState.StateObj.Usr_UID = 0;

        // redirect auf die homepage
        router.push({path: "/home"});
      });

      this.closeDropdown();
    },
    closeDropdown() {
      this.DropDownOpen = false;
    },
  },
  watch: {
    'AppState.StateObj.Usr_UID'() {
      this.fetchUserdata();
    }
  }
})

</script>

<style scoped>
.user-menu {
    display: flex;
    align-items: center;
    margin-right: 0.5em;

    padding-left: 0.5em;
    padding-right: 0.5em;

    border-radius: 1em;
    transition: 0.2s background-color;

    cursor: pointer;
}

.user-menu:hover {
  background: rgba(255, 255, 255, 0.1);
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
    background-position: center;
    background-size: cover;

    border-radius: 100%;
}
.login-lnk {
  color: white;
  text-decoration: none;

  margin-right: 1em;
}
.drop-down {
  position: absolute;
  top: 3em;
  right: 0;

  display: flex;
  flex-direction: column;

  width: 11em;

  background-color: #2F2F2F;
}
.drop-down button,a {
  padding: 0.5em;

  border: none;
  background-color: transparent;

  color: white;
  text-align: left;
  font-size: 0.8em;
  text-decoration: none;

  transition: 0.2s background-color;
}
.drop-down button:hover, .drop-down a:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.seperator {
  height: 1px;
  background-color: white;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>