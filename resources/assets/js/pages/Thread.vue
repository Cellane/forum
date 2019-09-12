<script>
import Replies from "../components/Replies"
import SubscribeButton from "../components/SubscribeButton"

export default {
  props: ["initialRepliesCount", "initialLocked"],

  data() {
    return {
      repliesCount: this.initialRepliesCount,
      locked: this.initialLocked
    }
  },

  components: {
    Replies,
    SubscribeButton
  }
}
</script>
