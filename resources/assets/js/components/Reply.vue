<script>
export default {
  props: ["attributes"],

  data() {
    return {
      editing: false,
      body: this.attributes.body,
      originalBody: this.attributes.body
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
          flash("Updated!")
        })
    },

    cancel() {
      this.body = this.originalBody
      this.editing = false
    }
  }
}
</script>
