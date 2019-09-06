<template>
  <li class="dropdown" v-if="notifications.length">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <span class="glyphicon glyphicon-bell"></span>
    </a>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications" :key="notification.id">
        <a
          :href="notification.data.link"
          @click.prevent="markAsRead(notification)"
        >
          {{ notification.data.message }}
        </a>
      </li>
    </ul>
  </li>
</template>

<script>
export default {
  data() {
    return {
      notifications: false
    }
  },

  created() {
    axios
      .get(`/profiles/${window.App.user.name}/notifications`)
      .then(({ data }) => {
        this.notifications = data
      })
  },

  methods: {
    markAsRead(notification) {
      axios
        .delete(
          `/profiles/${window.App.user.name}/notifications/${notification.id}`
        )
        .then(() => {
          window.location = notification.data.link
        })
    }
  }
}
</script>
