<template>
    <div class="main-page">
        <div class="header">
            <h1>Willkommen!</h1>
            <p>Im eS-Videoportal des <strong>BSZET Dresden</strong></p>

<!--            <div class="top-video">-->
<!--                <div class="left">-->
<!--                    <div class="thumbnail"></div>-->
<!--                </div>-->
<!--                <div class="right">-->
<!--                    <p class="title">Wir Möchten Wir Möchten Wir Möchten Wir Möchten</p>-->
<!--                    <p class="author">Ewan Schlesinger</p>-->

<!--                    <div class="info-bar">-->
<!--                        <ViewCount/>-->
<!--                        <RatingBar/>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="content">
            <ModeDepBox class="pad" @tabSwitch="updateTab" :Tabs='[
                {
                  ID: 0,
                  Title: "Neuste"
                },
                {
                  ID: 1,
                  Title: "Folge ich"
                },
              ]'
            >
                <template v-slot:content>
                    <transition mode="out-in">
                        <div v-if="tabIdx == 0">
                            <VideoList mode="all"/>
                        </div>
                        <div v-else>
                          <VideoList mode="flw"/>
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
import VideoList from "@/components/VideoList.vue";

export default defineComponent({
    name: "MainView",
    components: {
      VideoList,
        ModeDepBox,
        VideoDisplay,
    },
    data() {
        return {
            tabIdx: 0,
        }
    },
    methods: {
        updateTab(newIdx) {
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
}

.header {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  width: 100%;
  height: 15em;

  color: white;
  font-family: Inter;

  text-align: center;
  overflow: auto;

  background: rgb(135,212,122);
  background: linear-gradient(322deg, rgba(135,212,122,1) 0%, rgba(54,191,167,1) 100%);
}

.header > h1 {
    margin-bottom: 0.2em;
}

.header > p {
    margin-top: 0.5em;
    font-size: 1.5em;
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
  max-height: 100vh;
}

/* -------------------------------------------------------------------------- */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>