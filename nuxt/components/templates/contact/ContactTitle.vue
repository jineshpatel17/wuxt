<template>
  <div class="contact-title bg--action-gamma">
    <div class="row">
      <div class="block--full-width" v-if="titleDetail">
        <div class="contact-title__meta">{{ titleDetail.sectionName }}</div>
        <div class="contact-title__header">{{ titleDetail.heading }}</div>
        <div class="contact-title__header--copy">{{ titleDetail.headingData }}</div>
        <a class="contact-title__subtitle"
          v-if="titleDetail.subtitle &&
          titleDetail.subtitle.length"
          :href="regionUrl(titleDetail.subtitleUrl)">{{ titleDetail.subtitle }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactTitle',
  props: {
    titleDetail: {
      type: Object,
    },
  },
  methods: {
    regionUrl(url) {
      return `/${this.$getRegion()}/${url}`;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_contacttitle.scss';
</style>
