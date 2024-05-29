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
  props: ['mode', 'user', 'class', 'sort', 'videos'],
  data() {
    return {
      Videos: [],
    }
  },
  mounted() {
    this.get();
  },
  methods: {
    get() {
      if      (this.mode == 'all') this.getAll();
      else if (this.mode == 'usr') this.getForUser();
      else if (this.mode == 'flw') this.getForFollowed();
      else if (this.mode == 'cls') this.getForClass();
      else if (this.mode == 'lst') this.Videos = this.videos;
    },
    getAll() {
      fetch(`${Config.BackendHost}/videos`, {
        method: 'GET',
        credentials: 'include'
      })
      .then(res => res.json())
      .then(data => {
        this.Videos = data;
        this.sortVideos();
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
        this.sortVideos();
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
            this.sortVideos();
          })
    },
    getForClass() {
      fetch(`${Config.BackendHost}/videos/class/${this.class}`, {
        method: 'GET',
        credentials: 'include'
      })
          .then(res => res.json())
          .then(data => {
            this.Videos = data;
            this.sortVideos();
          })
    },
    sortVideos() {
      if (!this.sort) return;

      if (this.sort == 'newest') this.Videos.sort((a, b) => {
        return a.date > b.date ? -1 : 1;
      })

      if (this.sort == 'views') this.Videos.sort((a, b) => {
        return Number(a.views) > Number(b.views) ? -1 : 1;
      })

      if (this.sort == 'rating') this.Videos.sort((a, b) => {
        return Number(a.rating) > Number(b.rating) ? -1 : 1;
      })

      console.log(this.Videos);
    }
  },
  watch: {
    user() {
      this.get();
    }
  }
})
</script>

<style scoped>

</style>