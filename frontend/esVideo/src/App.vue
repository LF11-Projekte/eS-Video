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
.app {
  display: grid;
  grid-template-rows: 3em 1fr;

  width: 100%;
  height: 100%;
}
</style>
