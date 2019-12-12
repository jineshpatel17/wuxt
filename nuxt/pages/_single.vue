<template>
  <v-header/>
  <!-- <main class="wp__content">
    <section class="container">
      <h2>This is a homepage</h2>
      <div class="post" v-for="post in posts" :key="post.id">
        <h1>{{post.title.rendered}}</h1>
        <div class="post-content" v-html="post.excerpt.renderer"></div>
        <nuxt-link :to="{ name: 'single', params: {single: post.slug, slug: post.slug, id: post.id } }">
          Read More
        </nuxt-link>

      </div>
    </section>
  </main> -->
</template>

<script>
//   import axios from 'axios'
  import fetch from 'isomorphic-fetch'
  import VHeader from '@/components/templates/header/VHeader';
  import { locales } from '@/components/utils/i18n';

  export default {
    mounted () {
      const region = locales.find(o => o.code === this.$getRegion());
      console.log('test',region)
    },
    // created() {
    //     console.log('test', this.$nuxt._route.params)
    //     fetch('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/?slug='+this.$route.params.slug+'&status=publish').then((res) => {
    //         console.log('testing',   res)
    //         this.post = res.json
    //         console.log(res.json)
    //     })
    // },
    components: {
      // VCookie,
      VHeader
      // VFooter,
    },
    // Need to run a foreach over all possible post types, need to get list dynmaically.
    // Then append post type slug to Rest API URL.
    // create new mutation in store.commit foreach post type.
    // E.g. store.commit('page', pages)
    // This will async load all data.
    async asyncData({ store }) {
      // console.log(window.location.pathname)
      const response = await fetch('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/')
      const facts = await response.json()
      // console.log('testin', facts)
      store.commit('frontPagePosts', facts)
      return {facts}
    },

  // export default {
  //   fetch({ store }) {
  //     return axios.get('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/').then((res) => {
  //       console.log('test', res.data)
  //       store.commit('frontPagePosts', res.data)
  //     }).catch((error) => {
  //       console.log(error)
  //     })
  //   },
    computed: {
      posts() {
        return this.$store.state.posts
      }
    }
  }
</script>

<style lang="scss" scoped>
p {
  &:nth-child(1n + 2) {
    margin-top: 1rem;
  }
}

ul {
  margin-top: 1rem;

  li {
    &:nth-child(1n + 2) {
      margin-top: 1rem;
    }
  }
}

.bold {
  font-weight: 700;
}

.italic {
  font-style: italic;
}

code {
  padding: 3px 10px;

  background-color: #37495c;
  border-radius: 4px;

  color: #fff;
}
</style>
