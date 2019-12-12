<template>
  <div class="staggered-image">
    <div class="row">
      <div class="block--full-width">
        <div class="staggered-image__container">
          <div class="staggered-image__block" v-for="(item,index) in images" :key="index">
            <v-lazy-load-image :id="item.image.id"
              :blockClass="'staggered-image__image'"
              :mediaSizes="$imageDimensions('staggeredImageCS')" />
          </div>
        </div>
      </div>
    </div>
    <div class="staggered-image__bg" :style="{ background: bgColor}"></div>
  </div>
</template>

<script>

export default {
  name: 'StaggeredImage',
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
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_staggeredimage.scss';
</style>
