<template>
  <div class="article bg--light-alpha">
    <div class="row">
      <div class="block--full-width">
        <div class="article__container">
          <div class="article__text" v-html="articleContent" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VArticleDetail',
  props: {
    articleContent: {
      type: String,
      default: 'Some Text',
    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="sass" scoped>
@import './src/assets/scss/components/_varticledetail.scss';
@import './src/assets/scss/components/_wysiwyg.scss';
</style>
