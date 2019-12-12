<template>
  <div :class="classModifier">
    <div class="row">
      <div class="block--full-width">
        <div class="horizontal-images__container">
          <div class="horizontal-images__block"
            v-for="(item,index) in images" :key="index"
            v-infocus="type === 'double' ? 'animate' : 'animation'">
            <v-lazy-load-image
              :id="type === 'double' ? item.image.id : item.image"
              :blockClass="'horizontal-images__image'"
              :mediaSizes="$imageDimensions('horizontalImageCS')" />
          </div>
        </div>
      </div>
    </div>
    <div class="horizontal-images__bg" :style="{ background: bgColor}"></div>
  </div>
</template>

<script>

export default {
  name: 'HorizontalImages',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  props: {
    images: {
      type: Array,
    },
    bgColor: {
      type: String,
    },
    type: {
      type: String,
    },
    direction: {
      type: String,
    },
  },
  computed: {
    classModifier() {
      return [
        'horizontal-images',
        `horizontal-images--${this.type}`,
        this.direction ? `horizontal-images--${this.direction}` : '',
      ];
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_horizontalimages.scss';
</style>
