<template>
  <div class="qarticle bg--light-alpha">
    <div class="row">
      <div class="block--full-width">
        <div class="qarticle__bg qarticle--clr-pink">
          <div class="qarticle__box">
            <p class="qarticle__text">
              {{ quoteDetail.quote }}
            </p>
            <div class="qarticle__author">
              {{ quoteDetail.author_name }}, {{ quoteDetail.role }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VQuoteArticle',
  props: {
    quoteDetail: {
      type: Object,
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vquotearticle.scss';
</style>
