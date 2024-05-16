<template>
	<div class="frame">
		<div class="left">
			<div class="thumbnail"></div>
		</div>
		<div class="right">
			<p class="title">Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test </p>
			<p class="author">Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor Autor </p>

			<div class="info-bar">
				<ViewCount/>
				<RatingBar/>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent } from 'vue';
import ViewCount from '@/components/ViewCount.vue';
import RatingBar from '@/components/RatingBar.vue';

export default defineComponent({
    name: "VideoDisplay",
	components: {
		ViewCount,
		RatingBar,
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

	.left {
		aspect-ratio: 16/9;
		width: 13em;

		background: #636363;
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