<template>
  <div class="hero">
    <div class="hero__bg-image" :style="{ filter: `blur(${blurValue}px)` }">
      <v-lazy-load-image v-if="heroDetails.bgImage"
      :id="heroDetails.bgImage" :blockClass="'hero__image'"
      :mediaSizes="$imageDimensions('heroCover')"/>
    </div>
    <div class="hero__logo">
      <v-lazy-load-image v-if="heroDetails.logo"
      :id="heroDetails.logo" :blockClass="'hero__logo-image'"
      :mediaSizes="$imageDimensions('heroLogo')" />
    </div>
  </div>
</template>

<script>

export default {
  name: 'Hero',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  props: {
    heroDetails: {
      type: Object,
    },
  },
  data() {
    return {
      blurValue: 0,
    };
  },
  methods: {
    blurBg() {
      const scrollVal = window.scrollY;
      this.blurValue = scrollVal / 50;
      document.getElementsByClassName('hero__logo')[0].style.opacity =
      scrollVal === 0 ? 1 : 50 / scrollVal;
    },
  },
  mounted() {
    window.addEventListener('scroll', this.blurBg);
  },
  destroyed() {
    window.removeEventListener('scroll', this.blurBg);
  },
};
</script>

<style lang="scss">
@import "./src/assets/scss/components/_hero.scss";
</style>
