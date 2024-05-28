<template>
  <div class="rating">
    <div class="control">
      <div class="up" :class="{selected: Own == 1}" @click="rate(1);">
        <div class="icon">
          ▲
        </div>
        <p>
          {{Positiv}}
        </p>
      </div>
      <div class="down" :class="{selected: Own == -1}" @click="rate(-1);">
        <div class="icon">
          ▼
        </div>
        <p>
          {{Negative}}
        </p>
      </div>
    </div>
    <div class="bar">
      <div class="bar-filling" :style="`width: ${Rating*100}%;`"></div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {Config} from "@/config";

export default defineComponent({
  name: "RatingControl",
  props: ['video'],
  data() {
    return {
      Positiv: 0,
      Negative: 0,
      Rating: 0,
      Own: 0,
    }
  },
  mounted() {
    this.fetchRating();
  },
  methods: {
    fetchRating() {
      fetch(`${Config.BackendHost}/rating/${this.video}`, {
        credentials: "include"
      })
          .then(res => res.json())
          .then(data => {
            this.Positiv  = data['positive-ratings'];
            this.Negative = data['negative-ratings'];
            this.Rating   = data['rating'];
            this.Own      = data['your-rating'];
          })
    },
    rate(rt: number) {
      if (rt == this.Own) {
        fetch(`${Config.BackendHost}/rating/${this.video}`, {
          method: 'DELETE',
          credentials: "include",
        })
        .then(value => {
          this.fetchRating();
        })
      }
      else {
        fetch(`${Config.BackendHost}/rating/${this.video}`, {
          method: 'PUT',
          credentials: "include",
          body: JSON.stringify({
            rating: rt
          }),
        })
        .then(value => {
          this.fetchRating();
        })
      }
    }
  },
  watch: {
    video() {
      this.fetchRating();
    }
  }
})
</script>

<style scoped>
.rating {
  display: grid;
  grid-template-rows: auto 5px;
}
.control {
  display: grid;
  grid-template-columns: 1fr 1fr;
  background: #2F2F2F;
}

.control > div {
  display: grid;
  grid-template-columns: auto 1fr;

  cursor: pointer;
}

.up:hover .icon {
  color: #18B21E;
}

.down:hover .icon {
  color: #ED6262;
}

.up.selected .icon {
  color: #18B21E;
}

.down.selected .icon {
  color: #ED6262;
}

.control p {
  display: flex;
  align-items: center;

  margin: 0;
  padding-right: 1.5em;

  color: white;
  font-size: 1.3em;
}

.control .icon {
  display: flex;
  justify-content: center;
  align-items: center;

  width: 1.2em;

  margin-left: 0.5em;
  padding-bottom: 0.2em;

  font-size: 1.5em;
  color: white;
}

.bar {
  width: 100%;

  background-color: white;
}

.bar-filling {
  height: 100%;

  background-color: #18B21E;
}
</style>