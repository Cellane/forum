<template>
  <input type="file" accept="image/*" @change="onChange" />
</template>

<script>
export default {
  methods: {
    onChange(event) {
      if (!event.target.files.length) {
        return
      }

      let file = event.target.files[0]
      let reader = new FileReader()

      reader.onload = event => {
        const src = event.target.result

        this.$emit("loaded", { src, file })
      }

      reader.readAsDataURL(file)
    }
  }
}
</script>
