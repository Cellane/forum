<script>
import Favorite from "./Favorite"

export default {
  props: ["attributes"],

  data() {
    return {
      editing: false,
      body: this.attributes.body
    }
  },

  methods: {
    update() {
      axios
        .patch(`/replies/${this.attributes.id}`, {
          body: this.body
        })
        .then(() => {
          this.editing = false
          this.attributes.body = this.body
          flash("Updated!")
        })
    },

    destroy() {
      axios.delete(`/replies/${this.attributes.id}`).then(() => {
        $(this.$el).fadeOut(700, () => {
          flash("Your reply has been deleted.")
        })
      })
    },

    cancel() {
      this.body = this.attributes.body
      this.editing = false
    }
  },

  components: {
    Favorite
  }
}
</script>
