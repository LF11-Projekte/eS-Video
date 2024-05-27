<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";

export default defineComponent({
  name: "LoginConfirm",
  mounted() {
      if(!this.$route.params.atp) return;
      if(!this.$route.query.token) return;

      fetch(`${Config.BackendHost}/session/confirm`, {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          attempt: this.$route.params.atp,
          token: this.$route.query.token,
        })
      })
      .finally(() => {
        window.close();
      })
  },
})
</script>

<template>
  <div>
    <h1>
      SKA-Anmeldung erfolgt...
    </h1>
  </div>
</template>

<style scoped>
  div {
    display: flex;
    justify-content: center;
  }

  h1 {
    color: white;
  }
</style>