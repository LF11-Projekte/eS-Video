<template>
  <div v-for="vid in Videos" :key="vid">
    <VideoDisplay :video="vid" />
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";
import VideoDisplay from "@/components/VideoDisplay.vue";

export default defineComponent({
  name: "VideoList",
  components: {VideoDisplay},
  props: ['mode', 'user'],
  data() {
    return {
      Videos: [],
    }
  },
  mounted() {
    if      (this.mode == 'all') this.getAll();
    else if (this.mode == 'usr') this.getForUser();
    else if (this.mode == 'flw') this.getForFollowed();
  },
  methods: {
    getAll() {
      fetch(`${Config.BackendHost}/videos`, {
        method: 'GET',
        credentials: 'include'
      })
      .then(res => res.json())
      .then(data => {
        this.Videos = data;
      })
    },
    getForUser() {
      fetch(`${Config.BackendHost}/videos/${this.user}`, {
        method: 'GET',
        credentials: 'include'
      })
      .then(res => res.json())
      .then(data => {
        this.Videos = data;
      })
    },
    getForFollowed() {
      fetch(`${Config.BackendHost}/videos/followed`, {
        method: 'GET',
        credentials: 'include'
      })
          .then(res => res.json())
          .then(data => {
            this.Videos = data;
          })
    }
  }
})
</script>

<style scoped>

</style>