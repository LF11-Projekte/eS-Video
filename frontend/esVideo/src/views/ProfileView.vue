<template>
  <div class="main-page">
    <div class="header">
        <div class="profile-picture" :style='`background-image: url(${ProfilePic});`'></div>
        <div class="profile-info">
          <h1>{{Username}}</h1>
          <p>{{Class}}</p>
        </div>
        <div class="follow-button">
            <button v-if="$route.params.uid != AppState.StateObj.Usr_UID" @click="doFollow()">
            <transition name="fade" mode="out-in">
                <p v-if="IsFollowing">Entfolgen</p>
                <p v-else>Folgen</p>
            </transition>
              <p>{{Followers}}</p>
            </button>
            <div v-else>
              <p>{{Followers}}</p>
              <p>Follower</p>
            </div>
        </div>
    </div>
    <div class="content">
      <ModeDepBox @tabSwitch="updateTab" :Tabs='[
                {
                  ID: 0,
                  Title: "Neuste"
                },
                {
                  ID: 1,
                  Title: "Meistgeklickt"
                },
                {
                  ID: 2,
                  Title: "Bestbewertet"
                },
              ]'
      >
        <template v-slot:content>
          <transition mode="out-in">
            <div v-if="tabIdx == 0">
              <div class="video-list">
                <VideoDisplay v-for="i in 10" :key="i"/>
              </div>
            </div>
            <div v-else>
              <div class="video-list">
                <VideoDisplay v-for="i in 2" :key="i"/>
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
import ModeDepBox from '@/components/ModeDepBox.vue';
import VideoDisplay from '@/components/VideoDisplay.vue';
import {Config} from "@/config";
import {AppState} from "@/state";

export default defineComponent({
  name: "ProfileView",
  computed: {
    AppState() {
      return AppState
    },
    Config() {
      return Config
    }
  },
  components: {
    ModeDepBox,
    VideoDisplay,
  },
  data() {
    return {
      Username: "",
      Class: "",
      ProfilePic: "",

      Followers: 0,
      IsFollowing: false,

      tabIdx: 0,
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/user/${this.$route.params.uid}`, {
      credentials: "include"
    })
        .then(res => {
          if (!res.ok) return;

          res.json().then(obj => {
            this.Username = obj['displayName'];
            this.Class = obj['className'];
            this.ProfilePic = obj['profilePicture'];
          })
        });

    this.fetchFollowers();
  },
  methods: {
    updateTab(newIdx: number) {
      this.tabIdx = newIdx;
    },
    fetchFollowers() {
      fetch(`${Config.BackendHost}/followers/${this.$route.params.uid}`, {
        credentials: "include"
      })
          .then(res => {
            if (!res.ok) return;

            res.json().then(obj => {
              this.Followers = obj['NumFollowers'];
              this.IsFollowing = obj['IsFollowing'];
            })
          })
    },
    doFollow() {
      if (!this.IsFollowing) {
        // Folgen
        fetch(`${Config.BackendHost}/follow/${this.$route.params.uid}`, {
          credentials: "include",
          method: "post"
        })
            .then(res => {
              if (!res.ok) return;

              this.fetchFollowers();
            })
      }
      else {
        // Entfolgen
        fetch(`${Config.BackendHost}/unfollow/${this.$route.params.uid}`, {
          credentials: "include",
          method: "post"
        })
            .then(res => {
              if (!res.ok) return;

              this.fetchFollowers();
            })
      }
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
  display: flex;
  flex-direction: row;
  align-items: center;

  width: 100%;
  height: 20em;

  color: white;
  font-family: Inter;

  text-align: center;
  overflow: auto;

  background: linear-gradient(322deg, rgb(122, 148, 212) 0%, rgb(191, 54, 75) 100%);
}

.header {
  display: flex;
  flex-direction: row;
  align-items: center;

  padding-left: 2em;
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
</style>