<template>
  <div class="row">
    <div class="block--full-width">
      <div class="image-caption">
        {{ captionText }}
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ImageCaption',
  props: {
    captionText: {
      type: String,
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_imagecaption.scss';
</style>
