'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  HTTP_HOST: process.env.HTTP_HOST === undefined
    ? '"localhost"'
    : `"${process.env.HTTP_HOST}"`,
})
