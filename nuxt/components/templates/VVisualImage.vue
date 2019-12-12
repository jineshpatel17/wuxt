<template>
  <div class="visual-img" :style="{ backgroundImage: `url(${fetchUrl})` }"></div>
</template>

<script>
export default {
  name: 'VVisualImage',
  props: {
    bgImages: {
      type: Object,
    },
  },
  computed: {
    fetchUrl() {
      const deviceWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
      if (deviceWidth > 1260) return this.bgImages.lgUrl;
      else if (deviceWidth < 1260 && deviceWidth > 767) return this.bgImages.mdUrl;
      return this.bgImages.smUrl;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vvisualimage.scss';
</style>
