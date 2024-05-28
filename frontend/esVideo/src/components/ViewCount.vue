<template>
    <div>
        <img src="@/assets/img/eye.png" alt="">
        <p>{{views}}</p>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "ViewCount",
  props: ['views']
});
</script>

<style scoped>
img {
    width: 1.5em;
}

div {
    display: flex;
    align-items: center;
}

p {
    margin: 0;
    margin-left: 0.5em;
}
</style>