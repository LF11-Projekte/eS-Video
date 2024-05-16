<script setup lang="ts">
import { RouterView } from 'vue-router'
import HeaderBar from './components/HeaderBar.vue';
</script>

<template>
    <div class="app">
        <HeaderBar/>
        <main>
          <RouterView />
        </main>
    </div>

</template>

<style>
* {
  box-sizing: border-box;
}

main, #app {
  height: 100%;
  max-height: 100%;
  overflow: hidden;
}

.app {
  display: grid;
  grid-template-rows: 3em 1fr;

  width: 100%;
  height: 100%;
}
</style>
