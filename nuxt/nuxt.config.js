const axios = require('axios')
const pkg = require('./package')

module.exports = {
  server: {
    host: '0.0.0.0'
  },
  mode: 'universal',

  env: {
    WUXT_PORT_BACKEND: process.env.WUXT_PORT_BACKEND || '3080'
  },

  /*
   ** Headers of the page
   */
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description }
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
  },

  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#333' },

  /*
   ** Global CSS
   */
  css: ['@/assets/styles/main.scss'],

  /*
   ** Plugins to load before mounting the App
   */
  plugins: [{ src: '~/plugins/wp-api-docker-connector', ssr: false }, { src: '~/plugins/global-components', ssr: false }],

  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    '~/modules/static/',
    [
      '~/modules/wp-api/index',
      {
        endpoint:
          'http://' +
          (process.env.WUXT_WP_CONTAINER
            ? process.env.WUXT_WP_CONTAINER
            : 'wp.wuxt') +
          ':80/wp-json/'
      }
    ]
  ],
  /*
   ** Axios module configuration
   */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
  },

  /*
   ** Build configuration
   */
  // buildDir: '../functions'
  build: {
    // publicPath: '/',
    // vendor: ['isomorphic-fetch'],
    // extractCSS: true,
    // babel: {
    //   presets: [
    //     'es2015',
    //     'stage-0'
    //   ],
    //   plugins: ["transform-runtime", {
    //     "polyfill": true,
    //     "regenerator": true
    //   }]
    // },

    /*
     ** You can extend webpack config here
     */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          // loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  },

  generate: {
    routes: () => {
      const posts = []
      return axios
        .get('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/')
        .then(res => {
          console.log('testing', res)
          for (let index = 0; index < res.data.length; index++) {
            posts.push(res.data[index].slug)
          }
          return posts
        })
    }
  }
}
