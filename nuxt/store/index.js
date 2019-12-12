export const state = () => ({
  posts: [],
  region: []
})

export const mutations = {
  frontPagePosts(state, posts) {
    state.posts = posts
  },
  region(state, region) {
    state.region = region
  }
}

