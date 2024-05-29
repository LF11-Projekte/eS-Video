<template>
    <input type="text" placeholder="Suche" v-model="Query" @change="search()">
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'SearchBar',
  data() {
    return {
      Query: ""
    }
  },
  methods: {
    search() {
      this.$router.push({path: `/search/${encodeURI(this.Query)}`});
    }
  }
})
</script>

<style scoped>
input {
    min-width: 20em;

    padding: 0.5em;

    font-size: 0.8em;
    text-align: center;

    border: none;
    border-radius: 0.5em;
    outline: none;

    color: white;
    background-color: #1C1C1C;
}
</style>