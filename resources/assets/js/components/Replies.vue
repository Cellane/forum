<template>
  <div>
    <div v-for="(reply, index) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
  </div>
</template>

<script>
import Reply from "./Reply"

export default {
  props: ["data"],

  data() {
    return {
      items: this.data
    }
  },

  methods: {
    remove(index) {
      this.items.splice(index, 1)
      this.$emit("removed")
      flash("Reply was deleted.")
    }
  },

  components: {
    Reply
  }
}
</script>
