<template>
	<div class="frame" @click="watch()">
		<div class="left">
			<div class="thumbnail" :style="`background-image: url(${Config.BackendHost}${video.thumbnail});`"></div>
		</div>
		<div class="right">
			<p class="title">{{video.title}}</p>
			<router-link :to="{path: `/profile/${video.owner}`}" class="author">{{Owner}}</router-link>

			<div class="info-bar">
				<ViewCount :views="video.views"/>
				<RatingBar :rating="video.rating"/>
			</div>
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ViewCount from '@/components/ViewCount.vue';
import RatingBar from '@/components/RatingBar.vue';
import {Config} from "@/config";

export default defineComponent({
  name: "VideoDisplay",
  computed: {
    Config() {
      return Config
    }
  },
	components: {
		ViewCount,
		RatingBar,
	},
  props: ['video'],
  data() {
    return {
      Owner: "",
    }
  },
  mounted() {
    fetch(`${Config.BackendHost}/user/${this.video.owner}`, {
      credentials: "include"
    })
    .then(res => {
      if (!res.ok) return;

      res.json().then(obj => {
        this.Owner = obj['displayName'];
      })
    });
  },
  methods: {
    watch() {
      this.$router.push({path: `/video/${this.video.key}`});
    }
  }
})
</script>

<style scoped>
	.frame {
		display: grid;
		grid-template-columns: auto 1fr;

		width: 100%;
		padding: 0.5em;

		cursor: pointer;
		transition: 0.2s background-color;
	}

	.frame:hover {
		background: #505050;
	}

	.left .thumbnail {
		aspect-ratio: 16/9;
		width: 13em;

		background: #636363;

    background-position: center;
    background-size: cover;
	}

	.right {
		display: flex;
		flex-direction: column;
		overflow: hidden;
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

		max-width: 100%;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;

		text-align: left;
		font-size: 1.1em;
    text-decoration: none;

    color: rgba(255, 255, 255, 0.70);

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