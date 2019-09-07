<template>
  <div
    class="alert alert-flash"
    :class="`alert-${level}`"
    role="alert"
    v-show="show"
  >
    {{ data.message }}
  </div>
</template>

<script>
export default {
  props: ["initialData"],

  data() {
    return {
      data: {},
      show: false
    }
  },

  methods: {
    flash() {
      this.show = true

      this.hide()
    },

    hide() {
      setTimeout(() => {
        this.show = false
      }, 3000)
    }
  },

  computed: {
    level() {
      return this.data.level || "success"
    }
  },

  watch: {
    data() {
      if (this.data.message !== null) {
        this.flash()
      }
    }
  },

  created() {
    if (this.initialData) {
      this.data = this.initialData
    }

    window.events.$on("flash", data => (this.data = data))
  }
}
</script>

<style>
.alert-flash {
  position: fixed;
  right: 25px;
  bottom: 25px;
}
</style>
