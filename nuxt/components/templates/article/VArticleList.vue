<template>
  <div class="article-list">
    <div class="row">
      <div class="block--full-width">
        <div class="article-list__container">
          <div @click="allNews" class="article-list__item article-list__item--left">
            <a class="article-list__link-container">
              <span class="icon icon--widget">
                <span class="icon icon--widget__box"
                  v-for="item in 4" :key="item"></span>
              </span>
              <span class="article-list__link">{{this.articleName}}</span>
            </a>
          </div>
          <div class="article-list__item article-list__item--right">
            <a v-if="article.previous" :href="articleRedirect" class="article-list__link">
              <span class="article-list__text">Next</span>
              <span class="icon icon--chevron icon--chevron-right"></span>
            </a>
            <a v-if="article.previous" :href="articleRedirect" class="article-list__blurb">
              {{article.previous.title}}
              </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VArticleList',
  props: {
    theme: {
      type: String,
      default: 'light',
    },
    subTheme: {
      type: String,
      default: 'alpha',
    },
    /**
     * articleName is used for dynamically manage 'Read More' name.
     * @type {String}
     */
    articleName: {
      type: String,
      required: true,
    },
    article: {
      type: Object,
      required: true,
    },
    cardType: {
      type: String,
    },
  },
  methods: {
    allNews() {
      this.$router.push(`/${this.$getRegion()}/${this.cardType}`);
    },
  },
  computed: {
    articleRedirect() {
      return `/${this.$getRegion()}/${this.cardType}/${this.article.previous.slug}`;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_varticlelist.scss';
</style>
