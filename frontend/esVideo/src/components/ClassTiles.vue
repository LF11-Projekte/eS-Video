<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "ClassTiles",
  props: ['classes'],
  methods: {
    goToClass(classObj: any) {
      this.$router.push({path: `/class/${classObj.cid}`});
    }
  }
})
</script>

<template>
  <div class="classes">
    <div class="class" v-for="cls in classes" :key="cls" @click="goToClass(cls)">
      {{cls.name}}
    </div>
  </div>
</template>

<style scoped>
.classes {
  display: flex;
  gap: 0.5em;

  width: 100%;

  font-weight: bold;
  overflow-x: scroll;
}

.class {
  display: flex;
  flex-direction: column;
  align-items: center;

  padding: 1em;
  width: 13em;

  cursor: pointer;
  border-radius: 1em;

  color: white;
  background: #2F2F2F;

  transition: background-color 0.2s;
}

.class:hover {
  background: #535353;
}

</style>