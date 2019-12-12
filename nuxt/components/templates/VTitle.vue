<template>
  <div class="row">
    <div class="block--full-width">

      <div :class="`section-title section-title--${this.$currentHeroOverlay.theme}`">
          <div class="section-title__meta">
              <span v-if="titleType === 'articleDetail'" class="section-title__datetime">
                {{ titleDetail.date | moment("MM/DD/YYYY") }}
              </span>
              <span class="section-title__seperator" v-if="titleType === 'articleDetail'"></span>
              <span class="section-title__category">{{ titleDetail.sectionName }}</span>
          </div>
          <h1 class="section-title__title"
            :inner-html.prop="titleDetail.title | unescape">
          </h1>
          <div class="section-title__subtitle"
            :inner-html.prop="titleDetail.subTitle | unescape">
          </div>
          <div class="section-title__author"
            v-if="titleType === 'articleDetail' && titleDetail.author">
            <span class="section-title__author-name">{{ titleDetail.author.name }}</span>
            <span class="section-title__author-role">{{ titleDetail.author.role }}</span>
          </div>
          <div class="section-title__link" v-if="titleDetail.linkText">
            <v-button
              :btnText="titleDetail.linkText"
              :btnStyle="'blue'"
              :iconAlign="'chevron-right'"
              :isIcon="true"
              v-on:btnTrigger="redirectUrl(titleDetail.linkUrl)"
            />
          </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VTitle',
  data() {
    return {
      theme: '',
    };
  },
  computed: {
    titleModifier() {
      return `title-${this.titleType}`;
    },
  },
  components: {
    VButton: () => import('./VButton'),
  },
  props: {
    titleType: {
      type: String,
      default: 'normal',
    },
    titleDetail: {
      type: Object,
      required: true,
    },
  },
  methods: {
    redirectUrl(url) {
      if (url.includes('//')) {
        window.open(url, '_blank');
        return;
      }
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
  destroyed() {
    this.$currentHeroOverlay.theme = 'dark';
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vtitle.scss';
</style>
