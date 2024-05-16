<template>
    <div class="main-page">
        <div class="header">
            <h1>Willkommen!</h1>
            <p>Im eS-Videoportal des <strong>BSZET Dresden</strong></p>

            <div class="top-video">
                <div class="left">
                    <div class="thumbnail"></div>
                </div>
                <div class="right">
                    <p class="title">Wir Möchten Wir Möchten Wir Möchten Wir Möchten</p>
                    <p class="author">Ewan Schlesinger</p>

                    <div class="info-bar">
                        <ViewCount/>
                        <RatingBar/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ViewCount from '@/components/ViewCount.vue';
import RatingBar from '@/components/RatingBar.vue';

export default defineComponent({
    name: "MainPage",
    components: {
        ViewCount,
        RatingBar,
    }
})
</script>

<style scoped>
.main-page {
    background-color: #636363;
}

.header {
    display: flex;
    flex-direction: column;
    align-items: center;

    width: 100%;
    height: 20em;

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

.top-video {
    width: 40em;

    display: grid;
    grid-template-columns: 13em 1fr;

    background-color: #ffffff53;

    margin-top: 0.5em;
    padding: 0.5em;
}

.thumbnail {
    width: 13em;
    aspect-ratio: 16/9;
    background: white;
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
</style>