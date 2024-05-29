<script lang="ts">
import {defineComponent} from 'vue'
import UserTiles from "@/components/UserTiles.vue";
import ClassTiles from "@/components/ClassTiles.vue";
import VideoList from "@/components/VideoList.vue";
import {Config} from "@/config";
import userDisplay from "@/components/UserDisplay.vue";

export default defineComponent({
  name: "SearchView",
  computed: {
    userDisplay() {
      return userDisplay
    }
  },
  components: {VideoList, ClassTiles, UserTiles},
  data() {
    return {
      Users: [],
      Classes: [],
      Videos: []
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/search/${encodeURI(this.$route.params.search as string)}`, {
      credentials: "include",
    })
    .then(value => value.json())
    .then(data => {
      this.Users = data.users;
      this.Classes = data.classes;
      this.Videos = data.videos;
    })
  }
})
</script>

<template>
  <div class="content">
    <h1>
      Suche "{{$route.params.search}}"
    </h1>

    <div v-if="Users.length > 0 || Classes.length > 0" class="users-classes videos">
      <UserTiles  v-if="Users.length > 0" :users='Users'/>
      <ClassTiles v-if="Classes.length > 0" :classes='Classes'/>
    </div>

    <div class="videos">
      <p v-if="Videos.length == 0" class="no-videos">Es wurden keine Video gefunden</p>
      <VideoList v-else mode="lst" :videos="Videos"/>
    </div>
  </div>
</template>

<style scoped>
.content {
  height: 100%;

  padding: 1em;

  color: white;
  overflow: auto;
}

.users-classes {
  display: flex;
  flex-direction: column;
  gap: 1em;
}

.videos {
  width: 100%;

  margin-top: 1em;
  padding: 1em;

  border-radius: 1em;
  background-color: #414141;
}

.no-videos {
  width: 100%;
  margin: 0;

  font-size: 1.5em;
  text-align: center;
}
</style>