<template>
  <div class="outer-container">
	<div class="inner-container">
		<div class="selector">
			<button class="tab" v-for="tab in Tabs" :class="{ active: tab.ID == SelectedIndex }" @click="changeTab(tab.ID)" :key="tab">
				<p>{{tab.Title}}</p>
			</button>
		</div>
		<div class="content">
			<div class="slotin">
				<slot name="content"/>
			</div>
		</div>
	</div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: "ModeDepBox",
	data() {
		return {
			SelectedIndex: 0,
		}
	},
  props: [
      'Tabs'
  ],
	methods: {
		changeTab(idx: number) {
			this.SelectedIndex = idx;
			this.$emit("tabSwitch", idx)
		}
	}
});
</script>

<style scoped>
.outer-container {
	width: 100%;
	height: 100%;
  min-height: 20em;
}

.inner-container {
	display: grid;
	grid-template-rows: auto 1fr;

	width: 100%;
	height: 100%;
}

.selector {
	background: #2F2F2F;
}

button {
	margin-left: 1em;
	padding: 0;

	color: white;
	background: transparent;

	font-size: 1.1em;

	border: none;
	transition: 0.2s background-color;
}

button.active {
	background-color: #414141;
}

button p {
	margin: 0.5em;
}

.content {
	max-height: 100%;
	overflow: scroll;

	padding: 1em;

	font-size: 1.1em;
	position: relative;

	color: white;
	background: #414141;
}

.slotin {
	max-height: 100%;

}
</style>