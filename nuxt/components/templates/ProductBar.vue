<template>
  <div class="product-bar" v-if="productbarDetail">
    <div class="product-bar__bg">
      <div class="product-bar__container">
        <div class="product-bar__content">
          <div class="product-bar__meta">{{ productbarDetail.meta }}</div>
          <div class="product-bar__header">{{ productbarDetail.title }}</div>
          <div class="product-bar__seperator"></div>
          <div class="product-bar__copy"
            :inner-html.prop="productbarDetail.description">
          </div>
        </div>
        <div class="product-bar__ctc">
          <v-button
            :btnText="productbarDetail.button_label ? productbarDetail.button_label : ''"
            :btnStyle="`white-solid`"
            v-on:btnTrigger="onClickProductBar(productbarDetail.button_link)" />
        </div>
      </div>
      <div class="product-bar__bg-picture" v-if="this.productbarDetail.desktop_background_image">
        <picture>
          <source :srcset="this.productbarDetail.mobile_background_image"
            media="screen and (max-width: 767px)">
          <source :srcset="this.productbarDetail.tablet_background_image"
            media="screen and (max-width: 1260px)">
          <img class="product-bar__bg-image" :src="this.productbarDetail.desktop_background_image">
        </picture>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ProductBar',
  components: {
    VButton: () => import('@/shared/components/VButton'),
  },
  props: {
    productbarDetail: {
      type: Object,
    },
  },
  methods: {
    onClickProductBar(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_productbar.scss';
</style>
