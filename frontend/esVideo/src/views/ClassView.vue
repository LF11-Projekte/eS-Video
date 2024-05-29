<template>
  <div class="main-page">
    <div class="header">
      <div class="main">
        <div class="profile-info">
          <h1>{{Class}}</h1>
        </div>
      </div>
    </div>
    <div class="content">
      <ModeDepBox class="pad" @tabSwitch="updateTab" :Tabs='[
                {
                  ID: 0,
                  Title: "Mitglieder"
                },
                {
                  ID: 1,
                  Title: "Neuste"
                },
                {
                  ID: 2,
                  Title: "Meistgeklickt"
                },
                {
                  ID: 3,
                  Title: "Bestbewertet"
                },
              ]'
      >
        <template v-slot:content>
          <transition name="fade" mode="out-in">
            <div v-if="tabIdx == 0">
              <MemberGrid :class="$route.params.cid" />
            </div>
            <div v-else-if="tabIdx == 1">
              <div class="video-list">
                <VideoList mode="cls" :class="$route.params.cid" sort="newest"/>
              </div>
            </div>
            <div v-else-if="tabIdx == 2">
              <div class="video-list">
                <VideoList mode="cls" :class="$route.params.cid"  sort="views"/>
              </div>
            </div>
            <div v-else>
              <div class="video-list">
                <VideoList mode="cls" :class="$route.params.cid"  sort="rating"/>
              </div>
            </div>
          </transition>
        </template>
      </ModeDepBox>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {Config} from "@/config";
import {AppState} from "@/state";
import ModeDepBox from "@/components/ModeDepBox.vue";
import VideoList from "@/components/VideoList.vue";
import FollowTiles from "@/components/FollowTiles.vue";
import MemberGrid from "@/components/MemberGrid.vue";

export default defineComponent({
  name: "ClassView",
  components: {MemberGrid, FollowTiles, VideoList, ModeDepBox},
  computed: {
    AppState() {
      return AppState
    },
    Config() {
      return Config
    }
  },
  data() {
    return {
      Class: "",

      tabIdx: 0,
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/class/${this.$route.params.cid}`, {
      credentials: "include"
    })
        .then(res => {
          if (!res.ok) return;

          res.json().then(obj => {
            this.Class = obj['name'];
          })
        });
  },
  methods: {
    updateTab(newIdx: number) {
      this.tabIdx = newIdx;
    }
  }
})
</script>

<style scoped>
.main-page {
  display: grid;
  grid-template-rows: auto 1fr;

  height: 100%;

  background-color: #636363;
}

.header {
  width: 100%;
  padding-top: 4em;
  padding-bottom: 4em;

  color: white;
  font-family: Inter;

  text-align: center;
  overflow: auto;

  background: linear-gradient(322deg, rgb(212, 143, 122) 0%, rgb(191, 54, 54) 100%);
}

.header .main {
  display: flex;
  flex-direction: row;
  align-items: center;

  padding-left: 2em;
}

.header .desc-switch {
  display: inline-flex;
  justify-content: center;
  align-items: center;

  width: 1.3em;
  height: 1.3em;

  padding-right: 1px;
  padding-bottom: 2px;

  border-radius: 100%;
  background: rgba(255, 255, 255, 0.26);

  font-weight: bold;
  cursor: pointer;
}

.header .desc {
  margin-left: 14em;
  margin-right: 10em;

  padding: 1em;

  background: rgba(0, 0, 0, 0.2);
  text-align: left;

  white-space: pre;
}

.profile-picture {
  margin-right: 2em;
  width: 10em;

  aspect-ratio: 1;
  border-radius: 100%;
  border: 2px solid white;
  background: white;

  background-position: center;
  background-size: cover;
}

.profile-info {
  display: flex;
  flex-direction: column;

  text-align: left;
}

.header h1 {
  margin: 0;
}

.header p {
  margin: 0;
  font-size: 1.5em;
}

.follow-button {
  margin-left: auto;
  margin-right: 2em;
}

.follow-button button, .follow-button div {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  padding: 0.8em;
  gap: 1em;

  margin-left: 1em;
  margin-bottom: 2em;

  font-weight: 700;
  font-size: 0.8em;

  color: #a45b81;
  background-color: white;
  border: none;

  transition: color 0.2s;
}

.follow-button button {
  cursor: pointer;
}

.follow-button button:hover {
  color: #6a3b54;
  background-color: white;
}

.right {
  display: flex;
  flex-direction: column;

  overflow-x: hidden;
}

.right .title {
  margin: 0;
  margin-left: 0.5em;

  text-align: left;
  font-size: 1.3em;
  font-weight: 600;

  max-width: 100%;

  white-space: nowrap;
  overflow-x: hidden;
  text-overflow: ellipsis;
}

.right .author {
  margin: 0;
  margin-left: 0.6em;

  text-align: left;
  font-size: 1.1em;
}

.info-bar {
  display: flex;
  align-items: center;
  gap: 1em;

  margin-top: auto;
  margin-bottom: 0.5em;
  margin-left: 1em;
}

.content {
  overflow: hidden;
}


/* -------------------------------------------------------------------------- */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* -------------------------------------------------------------------------- */
.open-enter-active,
.open-leave-active {
  transition: transform 0.1s ease;
  transform-origin: top;
}

.open-enter-from,
.open-leave-to {
  transform: scaleY(0);
}
</style>