<template>
  <div class="single-image" :class="sectionModifier">
    <div :class="imageBlockModifiers"
      v-show="isImageLoaded"
      v-infocus="'animate'">
      <v-lazy-load-image
        @imageLoadedTrigger="imageLoadedHandler($event)"
        :id="imageId"
        :blockClass="'single-image__image'"
        :mediaSizes="$imageDimensions('singleImageCS')" />
    </div>
    <div class="single-image__bg" :style="{ background: bgColor}"></div>
  </div>
</template>

<script>

export default {
  name: 'SingleImage',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  data() {
    return {
      blockClass: 'single-image__container',
      isImageLoaded: false,
    };
  },
  computed: {
    imageBlockModifiers() {
      return [
        `${this.blockClass}`,
        `${this.blockClass}--align-${this.align}`,
        this.enableAnimation ? `animate__fade animate__fade--${this.align}` : '',
      ];
    },
    sectionModifier() {
      return [
        this.bgColor ? 'single-image--bg-required' : '',
      ];
    },
  },
  methods: {
    imageLoadedHandler() {
      this.isImageLoaded = true;
    },
  },
  props: {
    /**
     * @type {Object}
     */
    imageId: {
      type: Number,
    },
    bgColor: {
      type: String,
    },
    /**
     * @type {String}
     */
    align: {
      type: String,
    },
    enableAnimation: {
      type: Boolean,
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_singleimage.scss';
</style>
