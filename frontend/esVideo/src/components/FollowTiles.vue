<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";
import UserTiles from "@/components/UserTiles.vue";

export default defineComponent({
  name: "FollowTiles",
  components: {UserTiles},
  data() {
    return {
      Users: [],
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/following`, {
      credentials: "include"
    })
        .then(value => value.json())
        .then(data => {this.Users = data})
  }
})
</script>

<template>
  <UserTiles :users="Users"/>
</template>

<style scoped>

</style>