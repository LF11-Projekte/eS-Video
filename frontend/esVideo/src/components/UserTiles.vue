<script lang="ts">
import {defineComponent} from 'vue'
import UserTile from "@/components/UserTile.vue";

export default defineComponent({
  name: "UserTiles",
  components: {UserTile},
  props: ['users']
})
</script>

<template>
  <div class="users">
    <UserTile :user="user.ad" v-for="user in users" :key="user"/>
  </div>
</template>

<style scoped>
.users {
  display: flex;
  gap: 0.5em;

  width: 100%;
  overflow-x: scroll;
}

</style>