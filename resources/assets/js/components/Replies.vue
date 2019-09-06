<template>
  <div>
    <div v-for="(reply, index) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>

    <paginator :dataSet="dataSet" @changed="fetch"></paginator>

    <new-reply :endpoint="endpoint" @created="add"></new-reply>
  </div>
</template>

<script>
import NewReply from "./NewReply"
import Reply from "./Reply"
import Collection from "../mixins/Collection"

export default {
  data() {
    return {
      dataSet: false,
      endpoint: `${location.pathname}/replies`
    }
  },

  created() {
    this.fetch()
  },

  methods: {
    fetch(page) {
      if (!page) {
        const query = location.search.match(/page=(\d+)/)

        page = query ? query[1] : 1
      }

      axios.get(this.url(page)).then(this.refresh)
    },

    url(page) {
      return `${this.endpoint}?page=${page}`
    },

    refresh({ data }) {
      this.dataSet = data
      this.items = data.data
      window.scrollTo(0, 0)
    }
  },

  components: {
    NewReply,
    Reply
  },

  mixins: [Collection]
}
</script>
