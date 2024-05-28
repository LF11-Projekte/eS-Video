<template>
  <div class="comment-box">
    <h1>Kommentare</h1>
    <div class="comment-form">
      <textarea placeholder="Kommentieren..." v-model="Comment"></textarea>
      <div class="submit" @click="comment();">+</div>
    </div>
    <div v-if="Comments.length > 0">
      <CommentInstance v-for="com in Comments" :key="com" :comment="com"/>
    </div>
    <div v-else class="no-comments">
      Keine Kommentare
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import CommentInstance from "@/components/CommentInstance.vue";
import {Config} from "@/config";

export default defineComponent({
  name: "CommentBox",
  components: {CommentInstance},
  props: ['video'],
  data() {
    return {
      Comments: [],
      Comment: ""
    }
  },
  mounted() {
    this.fetchComments();
  },
  methods: {
    fetchComments() {
      fetch(`${Config.BackendHost}/comment/${this.video}`, {
        credentials: "include"
      })
          .then(res => res.json())
          .then(data => {
            this.Comments = data;
          })
    },
    comment() {
      fetch(`${Config.BackendHost}/comment/${this.video}`, {
        method: 'POST',
        credentials: "include",
        body: JSON.stringify({
          comment: this.Comment
        })
      })
      .then(res => {
        this.Comment = "";
        this.fetchComments();
      })
    }
  },
  watch: {
    video() {
      this.fetchComments();
    }
  }
})
</script>

<style scoped>
.comment-box {
  margin-top: 1em;
  overflow: auto;

  color: white;
  background: #2F2F2F;
}

.comment-box .profile-pic {
  width: 3.5em;
  aspect-ratio: 1;

  margin: 1em;

  border-radius: 100%;
  background: white;
}

.comment-box h1 {
  margin: 1em;
  font-size: 1.3em;
}

.comment-box p {
  margin: 0;
  font-size: 1.3em;
}

.comment-form {
  display: grid;
  grid-template-columns: 1fr 3em;
  background: #1F1F1F;
}

.comment-form textarea {
  padding: 1em;
  height: 5em;

  border: none;
  outline: none;

  font-family: Inter;
  font-size: 1.2em;

  color: white;
  background: transparent;
}

.no-comments {
  padding: 1em;
  width: 100%;
  text-align: center;
}

.submit {
  display: flex;
  justify-content: center;
  align-items: center;

  font-size: 2em;
  font-weight: bold;

  cursor: pointer;

  background: #3691d1;
}
</style>