<script lang="ts">
import {defineComponent} from 'vue'
import UserTile from "@/components/UserTile.vue";
import {Config} from "@/config";

export default defineComponent({
  name: "MemberGrid",
  props: ['class'],
  components: {UserTile},
  data() {
    return {
      Users: [],
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/class/${this.class}/members`, {
      credentials: "include"
    })
        .then(value => value.json())
        .then(data => {this.Users = data})
  }
})
</script>

<template>
  <div class="grid">
    <UserTile :user="user.ad" v-for="user in Users" :key="user"/>
  </div>
</template>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: repeat( auto-fit, 13em );
  gap: 1em;
}
</style>