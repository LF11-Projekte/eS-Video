<template>
  <div class="main-page">
    <div class="header">
        <div class="profile-picture" :style='`background-image: url(${ProfilePic});`'></div>
        <div class="profile-info">
          <h1>{{Username}}</h1>
          <p>{{Class}}</p>
        </div>
    </div>
    <div class="content">
        <div class="popup shadow">
          <div class="p-header">
            Nutzereinstellungen
          </div>
          <div class="p-content">
            <div class="left">
              <label>Anzeigename</label>
              <input type="text" v-model="Username" @change="updateUsername()">
              <label>Profilbild</label>
              <input type="file" @change="uploadImage" accept="image/png,image/jpeg"/>
              <label>Beschreibung</label>
              <textarea v-model="Description" @change="updateDescription()"></textarea>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {Config} from "@/config";
import {AppState} from "@/state";
import * as events from "node:events";

export default defineComponent({
  name: "EditProfileView",
  computed: {
    AppState() {
      return AppState
    },
    Config() {
      return Config
    }
  },
  data() {
    return {
      Username: "",
      Description: "",
      Class: "",
      ProfilePic: "",

      Followers: 0,
      IsFollowing: false,

      tabIdx: 0,
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/user`, {
      credentials: "include"
    })
        .then(res => {
          if (!res.ok) return;

          res.json().then(obj => {
            this.Username = obj['displayName'];
            this.Description = obj['description'];
            this.Class = obj['className'];
            this.ProfilePic = obj['profilePicture'];
          })
        });
  },
  methods: {
    updateUsername() {
      fetch(`${Config.BackendHost}/user/displayname`, {
        method: 'PUT',
        credentials: "include",
        body: JSON.stringify({
          'displayName': this.Username,
        })
      })
          .then(value => console.log('saved'))
    },
    updateDescription() {
      fetch(`${Config.BackendHost}/user/description`, {
        method: 'PUT',
        credentials: "include",
        body: JSON.stringify({
          'description': this.Description,
        })
      })
          .then(value => console.log('saved'))
    },
    uploadImage(e: Event) {
      let data = new FormData();
      data.append('name', 'profile-pic');
      data.append('file', e.target!.files[0]);

      fetch(`${Config.BackendHost}/user/upload`, {
        method: 'POST',
        credentials: "include",
        body: data,
      })
          .then(value => console.log('saved'))
    }
  }
})
</script>

<style scoped>
.main-page {
  display: grid;
  grid-template-rows: auto 1fr;

  height: 100%;

  background-color: #636363;
}

.header {
  display: flex;
  flex-direction: row;
  align-items: center;

  width: 100%;
  height: 20em;

  color: white;
  font-family: Inter;

  text-align: center;
  overflow: auto;

  background: linear-gradient(322deg, rgb(122, 148, 212) 0%, rgb(191, 54, 75) 100%);
}

.header {
  display: flex;
  flex-direction: row;
  align-items: center;

  padding-left: 2em;
}

.profile-picture {
  margin-right: 2em;
  width: 10em;

  aspect-ratio: 1;
  border-radius: 100%;
  border: 2px solid white;
  background: white;

  background-position: center;
  background-size: cover;
}

.profile-info {
  display: flex;
  flex-direction: column;

  text-align: left;
}

.header h1 {
  margin: 0;
}

.header p {
  margin: 0;
  font-size: 1.5em;
}

.content {
  display: flex;
  justify-content: center;

  height: 100%;
  background: white;
  overflow: hidden;
}

.popup {
  margin-top: 2em;

  width: 50em;
  height: fit-content;
}

.popup .p-header {
  padding: 0.5em;

  color: white;
  background: #0D466F;

  font-size: 1.5em;
}

.popup .p-content .left {
  display: flex;
  flex-direction: column;

  padding: 1em;
  padding-top: 0;
}

.popup .p-content .left input {
  padding: 0.5em;

  border: none;
  outline: none;

  background-color: #EEEEEE;
}

.popup .p-content .left textarea {
  padding: 0.5em;
  height: 10em;

  border: none;
  outline: none;

  resize: vertical;

  background-color: #EEEEEE;
}

.popup .p-content .left label {
  margin-top: 1em;
}

.shadow {
  -webkit-box-shadow: 0px 1px 6px 3px rgba(0,0,0,0.15);
  box-shadow: 0px 1px 6px 3px rgba(0,0,0,0.15);
}
</style>