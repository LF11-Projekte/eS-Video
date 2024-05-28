<script setup lang="ts">
import { RouterView } from 'vue-router'
import HeaderBar from './components/HeaderBar.vue';
</script>

<template>
    <div class="app">
        <HeaderBar/>
        <main>
          <RouterView v-slot="{ Component }">
            <transition name="router-fade" mode="out-in">
                <component :is="Component" :key="$route.fullPath" class="background"/>
            </transition>
          </RouterView>
        </main>
    </div>

</template>

<style>
* {
  box-sizing: border-box;
}

main {
  height: 100%;
}

.background {
  background-color: #636363;
}

#app {
  height: 100%;
}

.app {
  display: grid;
  grid-template-rows: 3em 1fr;

  width: 100%;
  height: 100%;

  background-color: #1C1C1C;
}

.router-fade-enter-active {
  transition: transform 0.3s ease-out;
}
.router-fade-leave-active {
  transition: transform 0.3s ease-in;
}

.router-fade-enter-from {
  transform: translateY(-100vh);
}
.router-fade-leave-to {
  transform: translateY(100vh);
}
</style>
