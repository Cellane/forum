<script>
import Replies from "../components/Replies"
import SubscribeButton from "../components/SubscribeButton"

export default {
  props: ["thread"],

  data() {
    return {
      repliesCount: this.thread.replies_count,
      locked: this.thread.locked
    }
  },

  methods: {
    toggleLock() {
      const method = this.locked ? "delete" : "post"

      axios[method](`/locked-threads/${this.thread.slug}`).then(() => {
        this.locked = method === "post"
      })
    }
  },

  components: {
    Replies,
    SubscribeButton
  }
}
</script>
