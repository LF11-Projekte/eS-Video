<template>
  <div class="comment-display">
    <div class="profile-pic" :style='`background-image: url(${ProfilePic});`' @click="goToProfile()"></div>
    <div class="user-info">
      <div class="comment-header">
        <h1 @click="goToProfile()">{{Username}}</h1>
        <p v-if="comment.poster == AppState.StateObj.Usr_UID" @click="deleteComment();">Löschen</p>
      </div>
      <p>{{comment.comment}}</p>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";
import {AppState} from "@/state";

export default defineComponent({
  name: "CommentInstance",
  computed: {
    AppState() {
      return AppState
    }
  },
  props: ['comment', 'video'],
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
    },
    deleteComment() {
      fetch(`${Config.BackendHost}/comment/${this.video}/${this.comment.cid}`, {
        method: 'DELETE',
        credentials: "include"
      })
      .then(value => {
          this.$emit("refresh");
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
.comment-display {
  display: grid;
  grid-template-columns: auto 1fr;

  padding: 1em;

  color: white;
  background: #2F2F2F;
}

.comment-display .profile-pic {
  width: 3.5em;
  aspect-ratio: 1;

  margin-right: 1em;

  border-radius: 100%;
  cursor: pointer;

  background: white;
  background-size: cover;
  background-position: center;
}

.comment-display h1 {
  margin: 0;
  font-size: 1.3em;

  cursor: pointer;
}

.comment-display p {
  margin: 0;
  font-size: 1.3em;
}

.comment-header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  width: 100%;
}

.comment-header p {
  display: none;

  cursor: pointer;

  margin-left: auto;
  color: #f15858;
}

.comment-display:hover .comment-header p {
  display: initial;;
}
</style>