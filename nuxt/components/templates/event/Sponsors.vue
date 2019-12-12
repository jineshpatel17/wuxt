EventPartners<template>
  <div class="sponsors">
    <div class="row">
      <div class="block--full-width">
        <div class="sponsors__title" :inner-html.prop="this.sponsors.partner_text"></div>
        <div class="sponsors__list"
          v-if="this.sponsors.partner_logo && this.sponsors.partner_logo.length"
          :class="this.sponsors.partner_logo.length > 4 && 'sponsors__list--rows'">
          <div class="sponsors__logo"
            v-for="(item, index) in this.sponsors.partner_logo"
            :key="index">
            <v-lazy-load-image
              :mediaSizes="$imageDimensions('sponsorLogo')"
              :id="item.company_logo"
              :blockClass="`sponsors__image`" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Sponsors',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  props: ['sponsors'],
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_sponsors.scss';
</style>
