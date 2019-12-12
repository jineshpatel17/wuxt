<template>
  <div class="row">
    <div class="block--full-width">
      <div class="culture">
        <div class="culture__container">
          <div class="culture__title">{{ cultureDetails.headingText }}</div>
          <div class="culture__container--text">
            <div class="culture__header"
              :inner-html.prop="cultureDetails.introductionParagraph">
            </div>
            <div class="culture__copy" :inner-html.prop="cultureDetails.secondaryParagraph"></div>
          </div>
        </div>
        <!-- NJW-1058: convert three image to single -->
        <!-- <div class="culture__container--image">
          <div class="culture__images" v-for="image in cultureDetails.images" :key="image.id">
            <v-lazy-load-image :id="image.ID" :blockClass="'culture__image'" />
          </div>
        </div> -->
        <div class="culture__container--image" v-if="cultureDetails
          && cultureDetails.images && cultureDetails.images.length > 0">
          <v-lazy-load-image
            :id="cultureDetails.images[0].ID"
            :blockClass="'culture__image'"
            :mediaSizes="$imageDimensions('culture')"
            />
        </div>
        <div class="culture__container--cta">
          <v-button
            :btnText="cultureDetails.btnText"
            :btnStyle="'blue'"
            :iconAlign="'chevron-right'"
            :isIcon="true"
            v-on:btnTrigger="redirect"/>
          <router-link :to="regionUrl(`/${cultureDetails.ourVacanciesLinkUrl}`)"
          class="culture__cta link--slide-blue-underline">
            {{ cultureDetails.linkText }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Culture',
  props: {
    cultureDetails: {
      type: Object,
    },
  },
  components: {
    VButton: () => import('@/shared/components/VButton'),
    VLazyLoadImage: () => import('./VLazyLoadImage'),
  },
  methods: {
    redirect() {
      this.$router.push(`/${this.$getRegion()}/${this.cultureDetails.ourCultureLink}`);
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_culture.scss';
</style>
