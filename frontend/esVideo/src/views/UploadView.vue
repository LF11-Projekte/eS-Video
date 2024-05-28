<template>
  <div class="main-page">
    <div class="content">
      <div class="popup shadow">
        <div class="p-header">
          Hochladen
        </div>
        <div class="p-content">
          <transition name="fade" mode="out-in">
            <div class="form video-container" v-if="!HasVideoFile">
              <label class="file-label">
                <div class="video-select">
                  Video Auswählen
                </div>
                <input @change="selectVideo" type="file" class="hidden" accept="video/mp4"/>
              </label>
            </div>
            <div class="split-form" v-else>
              <div class="form">
                <label>Titel</label>
                <input type="text" v-model="Title">
                <label>Beschreibung</label>
                <textarea v-model="Description"></textarea>

                <div class="progress" :style="UploadPercentStyle">
                  <div class="progress-bar" :class="{done: VideoOk}"></div>
                  <div></div>
                </div>
              </div>
              <div class="form">
                <label>Vorschaubild</label>
                <label class="file-label">
                  <div class="preview" :style="ThumbnailStyle">
                    Ändern
                  </div>
                  <input @change="previewThumbnail" type="file" class="hidden" accept="image/png,image/jpeg"/>
                </label>

                <button class="submit-button" :class="{ready: ReadyToPublish}" @click="submitUpload">
                  Veröffentlichen
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {Config} from "@/config";
import axios from 'axios';

export default defineComponent({
  name: "UploadView",
  data() {
    return {
      HasVideoFile: false,
      ThumbnailURL: "",

      Title: "",
      Description: "",

      ThumbnailFile: null as File|null,

      VideoOk: false,
      VideoID: "",

      UploadPercent: 0,
    }
  },
  computed: {
    ThumbnailStyle() {
      return (!this.ThumbnailURL) ? "" : `background-image: url(${this.ThumbnailURL});`;
    },
    UploadPercentStyle() {
      if (this.VideoOk) {
        return `grid-template-columns: 100% 0%;`;
      } else {
        return `grid-template-columns: ${this.UploadPercent * 100}% 1fr;`;
      }
    },
    ReadyToPublish() {
      return this.VideoOk && this.ThumbnailFile != null && (this.Title) && (this.Description);
    }
  },
  methods: {
    selectVideo(e: Event) {
      let me = this;
      let data = new FormData();
      data.append('video', (e.target as HTMLInputElement)!.files![0]);

      axios.request({
        method: 'POST',
        url: `${Config.BackendHost}/video/upload`,
        withCredentials: true,
        data: data,
        onUploadProgress(progressEvent) {
            me.HasVideoFile = true;
            me.UploadPercent = progressEvent.progress ?? 0;
        },
      })
      .catch(reason => {
        console.log(reason);
        this.HasVideoFile = false;
      })
      .then(value => {

        // Upload fehlgeschlagen
        if (value.status != 200) {
          console.log(value);
          this.HasVideoFile = false;
        }

        // Upload OK
        else {
          this.UploadPercent = 1;
          this.VideoOk = true;
          this.VideoID = value.data.fileid;
        }
      });
    },
    previewThumbnail(e: Event) {
      this.ThumbnailFile = (e.target as HTMLInputElement)!.files![0];
      this.ThumbnailURL = URL.createObjectURL(e.target!.files[0]);
    },
    submitUpload() {
      // Wichtige Angaben fehlen
      if (!this.ReadyToPublish) return;

      // Ansonsten -> Video einsenden
      let data = new FormData();
      data.append('title', this.Title)
      data.append('description', this.Description)
      data.append('thumbnail', this.ThumbnailFile!)
      data.append('video', this.VideoID)

      fetch(`${Config.BackendHost}/video/submit?XDEBUG_SESSION=a`, {
        method: 'POST',
        credentials: "include",
        body: data
      })
      .then(value => {
          if (!value.ok) {
            this.HasVideoFile = false;
          }
          else {
            value.json().then(data => {
              this.$router.push({path: `/video/${data.vid}`});
            })
          }
      })
    }
  }
});
</script>

<style scoped>
.main-page {
  height: 100%;
}
.content {
  display: flex;
  justify-content: center;

  height: 100%;
  overflow: hidden;
}

.popup {
  margin-top: 2em;
  margin-bottom: 2em;

  width: 60em;
  height: fit-content;

  color: white;
  background: #1C1C1C;
}

.popup .p-header {
  padding: 0.5em;

  color: white;
  background: linear-gradient(322deg, rgb(212, 143, 122) 0%, rgb(191, 148, 54) 100%);

  font-size: 1.5em;
}

.popup .p-content .split-form {
  display: grid;
  grid-template-columns: 61.5% 1fr;
}

.popup .p-content .form {
  display: flex;
  flex-direction: column;

  padding: 1em;
  padding-top: 0;
}

.popup .p-content .form input {
  padding: 0.5em;

  border: none;
  outline: none;

  color: white;
  background-color: #414141;
}

.popup .p-content .form textarea {
  padding: 0.5em;
  height: 10em;

  border: none;
  outline: none;

  resize: none;

  color: white;
  background-color: #414141;
}

.popup .p-content .form label {
  margin-top: 1em;
}

.submit-button {
  margin-top: 2.4em;
  padding: 0.53em;

  border: none;
  outline: none;

  cursor: not-allowed;

  color: white;
  background-color: #414141;
}

.submit-button.ready {
  font-weight: bold;

  cursor: pointer;
  background: rgb(191, 148, 54);
}

.progress {
  display: grid;

  margin-top: 2em;
  height: 2em;

  background: #414141;
}

.preview {
  display: flex;
  justify-content: center;
  align-items: center;

  width: 100%;
  aspect-ratio: 16/9;

  cursor: pointer;

  background-size: cover;
  background-position: center;

  background-color: #414141;

  transition: opacity 0.2s;
}

.preview:hover {
  opacity: 0.8;
}

.video-select {
  display: flex;
  justify-content: center;
  align-items: center;

  margin: 2em;
  padding: 2em;

  cursor: pointer;
  transition: font-weight, font-size, margin 0.1s;

  background-color: #414141;
}

.video-select:hover {
  font-weight: bold;
  font-size: 1.5em;

  margin-top: 1.74em;

  background: linear-gradient(322deg, rgb(212, 143, 122) 0%, rgb(191, 148, 54) 100%);
}

.video-container {
  display: flex;
  justify-content: center;
}

.form .file-label {
  margin-top: 0 !important;
}

.hidden {
  display: none;
}

.progress-bar {
  background: linear-gradient(322deg, rgb(212, 143, 122) 0%, rgb(191, 148, 54) 100%);
}

.progress-bar.done {
  background: linear-gradient(322deg, rgb(163, 212, 122) 0%, rgb(177, 191, 54) 100%);
}

.shadow {
  -webkit-box-shadow: 0px 1px 6px 3px rgba(0,0,0,0.15);
  box-shadow: 0px 1px 6px 3px rgba(0,0,0,0.15);
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