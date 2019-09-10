const user = window.App.user

export default {
  updateReply(reply) {
    return reply.user_id === user.id
  },

  markBestReply(reply) {
    return reply.thread.creator.id === user.id
  }
}
