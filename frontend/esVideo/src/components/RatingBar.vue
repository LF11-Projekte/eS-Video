<template>
    <div class="bar">
        <div class="bar-filling" :style="`width: ${rating*100}%;`"></div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "RatingBar",
  props: ['rating']
});
</script>

<style scoped>
    .bar {
        width: 100%;
        height: 2px;

        background-color: white;
    }

    .bar-filling {
        height: 100%;

        background-color: #18B21E;
    }
</style>