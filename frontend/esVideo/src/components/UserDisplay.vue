<template>
  <div class="user-display" @click="goToProfile()">
    <div class="profile-pic" :style='`background-image: url(${ProfilePic});`'></div>
    <div class="user-info">
      <h1>{{Username}}</h1>
      <p>{{Class}}</p>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";

export default defineComponent({
  name: "UserDisplay",
  props: ['user'],
  data() {
    return {
      Username: "",
      Class: "",
      ProfilePic: ""
    }
  },
  methods: {
    goToProfile() {
      this.$router.push({path: `/profile/${this.user}`});
    }
  },
  watch: {
    user() {
      fetch(`${Config.BackendHost}/user/${this.user}`, {
        credentials: "include"
      })
          .then(res => res.json())
          .then(data => {
            this.Username   = data['displayName'];
            this.Class      = data['className'];
            this.ProfilePic = data['profilePicture'];
          })
    }
  }
})
</script>

<style scoped>
.user-display {
  display: flex;
  flex-direction: row;
  align-items: center;

  cursor: pointer;

  color: white;
  background: #2F2F2F;
}

.user-display .profile-pic {
  width: 3.5em;
  aspect-ratio: 1;

  margin: 1em;

  border-radius: 100%;

  background: white;
  background-size: cover;
  background-position: center;
}

.user-display h1 {
  margin: 0;
  font-size: 1.3em;
}

.user-display p {
  margin: 0;
  font-size: 1.3em;
}
</style>