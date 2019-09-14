<script>
import Replies from "../components/Replies"
import SubscribeButton from "../components/SubscribeButton"

export default {
  props: ["initialThread"],

  data() {
    return {
      thread: this.initialThread,
      form: {},
      editing: false
    }
  },

  created() {
    this.resetForm()
  },

  methods: {
    resetForm() {
      this.form.title = this.thread.title
      this.form.body = this.thread.body
    },

    toggleLock() {
      const method = this.thread.locked ? "delete" : "post"

      axios[method](`/locked-threads/${this.thread.slug}`).then(() => {
        this.thread.locked = method === "post"
      })
    },

    cancel() {
      this.resetForm()
      this.editing = false
    },

    update() {
      axios
        .patch(location.pathname, this.form)
        .then(() => {
          flash("Your thread has been updated!")
          this.thread.title = this.form.title
          this.thread.body = this.form.body
          this.editing = false
        })
        .catch(error => {
          flash("Something went wrong…", "danger")
          console.error(error)
        })
    }
  },

  components: {
    Replies,
    SubscribeButton
  }
}
</script>
