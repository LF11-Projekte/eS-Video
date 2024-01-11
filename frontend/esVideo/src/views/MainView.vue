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
                    <p class="title">Wir Möchten</p>
                    <p class="author">Ewan Schlesinger</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: "MainPage"
})
</script>

<style scoped>
.header {
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
    display: grid;
    grid-template-columns: 13em 20em;

    background-color: #ffffff53;

    padding: 0.5em;
}

.thumbnail {
    width: 13em;
    aspect-ratio: 16/9;
    background: white;
}

.right .title {
    text-align: left;
    font-size: 1.5em;
}
</style>