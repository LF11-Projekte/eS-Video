<template>
  <div class="user-tile" @click="goToProfile()">
    <div class="profile-pic" :style='`background-image: url(${ProfilePic});`'></div>
    <h1>{{Username}}</h1>
    <p>{{Class}}</p>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";

export default defineComponent({
  name: "UserTile",
  props: ['user'],
  data() {
    return {
      Username: "",
      Class: "",
      ProfilePic: ""
    }
  },
  mounted() {
      this.getUser();
  },
  methods: {
    getUser() {
      fetch(`${Config.BackendHost}/user/${this.user}`, {
        credentials: "include"
      })
          .then(res => res.json())
          .then(data => {
            this.Username   = data['displayName'];
            this.Class      = data['className'];
            this.ProfilePic = data['profilePicture'];
          })
    },
    goToProfile() {
      this.$router.push({path: `/profile/${this.user}`});
    }
  },
  watch: {
    user() {
      this.getUser();
    }
  }
})
</script>

<style scoped>
.user-tile {
  display: flex;
  flex-direction: column;
  align-items: center;

  padding: 1em;
  width: 13em;

  cursor: pointer;
  border-radius: 1em;

  color: white;
  background: #2F2F2F;

  transition: background-color 0.2s;
}

.user-tile:hover {
  background: #535353;
}

.user-tile:hover .profile-pic {
  width: 8em;
}

.user-tile .profile-pic {
  width: 7em;
  aspect-ratio: 1;

  margin: 1em;

  border-radius: 100%;

  background: white;
  background-size: cover;
  background-position: center;

  transition: width 0.2s;
}

.user-tile h1 {
  margin: 0;
  font-size: 1.3em;
  font-weight: bold !important;
}

.user-tile p {
  margin: 0;
  font-size: 1.3em;
}
</style>