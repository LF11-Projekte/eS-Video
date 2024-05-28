<template>
  <div class="user-display">
    <div class="profile-pic" :style='`background-image: url(${ProfilePic});`' @click="goToProfile()"></div>
    <div class="user-info">
      <h1 @click="goToProfile()">{{Username}}</h1>
      <p>{{comment.comment}}</p>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";

export default defineComponent({
  name: "CommentInstance",
  props: ['comment'],
  data() {
    return {
      Username: "",
      ProfilePic: ""
    }
  },
  mounted() {
    this.fetchUser();
  },
  methods: {
    goToProfile() {
      this.$router.push({path: `/profile/${this.comment.poster}`});
    },
    fetchUser() {
      fetch(`${Config.BackendHost}/user/${this.comment.poster}`, {
        credentials: "include"
      })
          .then(res => res.json())
          .then(data => {
            this.Username   = data['displayName'];
            this.ProfilePic = data['profilePicture'];
          })
    }
  },
  watch: {
    user() {
      this.fetchUser();
    }
  }
})
</script>

<style scoped>
.user-display {
  display: flex;
  flex-direction: row;
  align-items: start;

  padding: 1em;

  color: white;
  background: #2F2F2F;
}

.user-display .profile-pic {
  width: 3.5em;
  aspect-ratio: 1;

  margin-right: 1em;

  border-radius: 100%;
  cursor: pointer;

  background: white;
  background-size: cover;
  background-position: center;
}

.user-display h1 {
  margin: 0;
  font-size: 1.3em;

  cursor: pointer;
}

.user-display p {
  margin: 0;
  font-size: 1.3em;
}
</style>