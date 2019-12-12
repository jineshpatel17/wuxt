<template>
  <div class="row">
    <div class="block--full-width">
      <div :class="scrollVisualAlignModifiers">
        <div class="scroll-visual__caption">
          <div class="scroll-visual__title">{{ scrollVisualDetail.title }}</div>
          <p class="scroll-visual__copy" :inner-html.prop="scrollVisualDetail.description"></p>
        </div>
        <div class="scroll-visual__visual" v-if="scrollVisualDetail.imageId">
          <v-lazy-load-image :id="scrollVisualDetail.imageId"
          :blockClass="'scroll-visual__image'"
          :mediaSizes="$imageDimensions('scrollVisualCS')" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ScrollVisual',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  data() {
    return {
      blockClass: 'scroll-visual',
    };
  },
  computed: {
    scrollVisualAlignModifiers() {
      return [
        `${this.blockClass}`,
        this.scrollVisualDetail.align === 'right' ? `${this.blockClass}--right` : '',
      ];
    },
  },
  props: {
    scrollVisualDetail: {
      type: Object,
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_scrollVisual.scss';
</style>
