const user = window.App.user

export default {
  owns(model, prop = "user_id") {
    return model[prop] === user.id
  }
}
