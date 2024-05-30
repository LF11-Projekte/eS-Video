<template>
  <div v-if="!Found">
    <h1 class="not-found">Das gewünschte Video konnte nicht abgerufen werden!</h1>
  </div>
  <div v-else class="layout">
    <div class="head">
      <div class="title">
        <h1>{{Title}}</h1>
      </div>
      <div class="title-info">
        <ViewCount class="vc" :views="Views"/>
        <RatingControl :video="$route.params.key"/>
      </div>
    </div>
    <div class="video-container">
      <video class="player" controls :src="`${Config.BackendHost}${File}`"/>
    </div>
    <div class="under-video">
      <ModeDepBox @tabSwitch="updateTab" :Tabs='[
              {
                ID: 0,
                Title: "Mehr von diesem Nutzer"
              },
              {
                ID: 1,
                Title: "Neuste"
              },
            ]'
      >
        <template v-slot:content>
          <transition mode="out-in">
            <div v-if="tabIdx == 0">
              <VideoList mode="usr" :user="Owner" sort="newest"/>
            </div>
            <div v-else>
              <VideoList mode="all" sort="newest"/>
            </div>
          </transition>
        </template>
      </ModeDepBox>
      <div class="video-info">
        <UserDisplay :user="Owner"/>

        <div class="description">
          <h1>Über dieses Video</h1>
          <div class="control-center" v-if="Owner == AppState.StateObj.Usr_UID">
<!--            <p class="edit">Bearbeiten</p>-->
            <p class="delete" @click="deleteVideo()">Löschen</p>
          </div>
          <div class="description-container">
            <p>{{Description}}</p>
          </div>
        </div>

        <CommentBox :video="$route.params.key"/>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import RatingControl from "@/components/RatingControl.vue";
import UserDisplay from "@/components/UserDisplay.vue";
import ModeDepBox from "@/components/ModeDepBox.vue";
import CommentBox from "@/components/CommentBox.vue";
import VideoList from "@/components/VideoList.vue";
import ViewCount from "@/components/ViewCount.vue";
import {Config} from "@/config";
import {AppState} from "@/state";

export default defineComponent({
  name: "VideoView",
  computed: {
    AppState() {
      return AppState
    },
    Config() {
      return Config
    }
  },
  components: {ViewCount, VideoList, ModeDepBox, CommentBox, UserDisplay, RatingControl},
  data() {
    return {
      Title: "",
      Description: "",
      File: "",
      Views: 0,

      Owner: "",
      Found: true,

      tabIdx: 0,
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/video/${this.$route.params.key}`, {
      credentials: "include"
    })
    .then(res => res.json())
        .catch(err => {this.Found = false; })
    .then((value: any) => {
      console.log(value);
       this.Title       = value.title;
       this.Description = value.description;
       this.File        = value.file;
       this.Views       = value.views;
       this.Owner       = value.owner;
    });
  },
  methods: {
    updateTab(newIdx) {
      this.tabIdx = newIdx;
    },
    deleteVideo() {
      fetch(`${Config.BackendHost}/video/${this.$route.params.key}`, {
        method: 'DELETE',
        credentials: "include"
      })
      .then(value => {
        window.location.reload();
      })
    }
  }
});
</script>

<style scoped>
.not-found {
  padding: 1em;
  text-align: center;

  color: white;
  background: #831b1b;
}

.layout {
  display: flex;
  flex-direction: column;

  height: 100%;

  padding: 1em;
  font-family: Inter;
}

.head {
  display: grid;
  grid-template-columns: 1fr auto;

  padding-top: 1em;
  padding-bottom: 1em;
}

.title h1 {
  margin: 0;
  color: white;
  font-weight: 700;
}

.title-info {
  display: flex;
  flex-direction: row;
  gap: 1em;

  color: white;
}

.title-info .vc {
  font-size: 1.3em;
  margin-bottom: 5px;
}

.video-container {
  display: flex;
  justify-content: center;
  background: black;
}

.player {
  height: 40vw;
  background: white;
}

.under-video {
  display: grid;
  grid-template-columns: 1fr 30em;
  gap: 1em;

  margin-top: 2em;
}

.description {
  margin-top: 1em;

  overflow: auto;

  color: white;
  background: #2F2F2F;
}

.description h1 {
  margin: 1em;
  font-size: 1.3em;
}

.description p {
  margin: 1em;
  margin-top: 0.5em;
  font-size: 1.3em;

  white-space: pre;
}

.control-center {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1em;

  padding: 1em;
  background: #1F1F1F;
}

.control-center p {
  width: 100%;
  margin: 0;

  text-align: center;

  cursor: pointer;
}

.control-center p:hover {
  text-decoration: underline;
}

.control-center .edit {
  color: #4da0dd;
}

.control-center .delete {
  color: #f15858;
}

.description-container {
  width: 100%;
  overflow-x: scroll;
}
</style>