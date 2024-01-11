<template>
     <nav>
        <EsLogo/>
        <div></div>
        <SearchBar/>
        <div></div>
        <UserMenu/>
    </nav>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import EsLogo from '@/components/EsLogo.vue';
import SearchBar from '@/components/SearchBar.vue';
import UserMenu from '@/components/UserMenu.vue';

export default defineComponent({
    name: "HeaderBar",
    components: {
        EsLogo,
        SearchBar,
        UserMenu,
    }
})
</script>

<style scoped>
nav {
    display: grid;
    grid-template-columns: 5em 1fr 2fr 1fr auto;
    align-items: center;

    background-color: #2F2F2F;

    z-index: 1;
    -webkit-box-shadow: 0px 9px 25px 0px rgba(0,0,0,0.59); 
    box-shadow: 0px 9px 15px -5px rgba(0,0,0,0.59);
}

nav svg {
    width: 3em;
    margin-left: 0.5em;
}
</style>