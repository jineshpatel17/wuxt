<template>
<picture class="responsive-image">
    <source
      v-for="(image, key) in this.images"
      :key="key"
      :srcset="image.source_url"
      :type="image.mime_type"
      :media="mediaQuery(image, key)">
    <transition name="fade">
      <img
        :alt="altText"
        :src="this.fullImage.source_url"
        :class="[blockClass, magnifier]"
        v-on:load="onLoaded"
        v-show="loaded"/>
    </transition>
  </picture>
</template>

<script>
export default {
  name: 'VResponsiveImage',
  props: ['id', 'blockClass', 'magnifier'],
  data() {
    return {
      images: {},
      fullImage: {},
      imageID: 0,
      loaded: false,
      altText: '',
    };
  },
  watch: {
    async id(value) {
      if (this.imageID !== value) {
        if (this.id) {
          const apiUrl = `/wp/v2/media/${this.id}`;
          let res = {};
          res = await this.$apiCall(apiUrl, false, 'object');
          this.apiResponse(res);
        }
      }
      this.imageID = value;
    },
  },
  methods: {
    /**
     * TODO Move this to a global function
     */
    mediaQuery: function mediaQuery(image, key) {
      const MIN_OR_MAX = key === 'full' ? 'min-width' : 'max-width';
      const STRING = `screen and (${MIN_OR_MAX}: ${image.width}px)`;
      return STRING;
    },
    apiResponse(response) {
      this.images = response.media_details.sizes;
      this.fullImage = response.media_details.sizes.full;
      this.altText = response.alt_text;
    },
    onLoaded() {
      this.loaded = true;
    },
  },
  async created() {
    const apiUrl = `/wp/v2/media/${this.id}`;
    let res = {};
    res = await this.$apiCall(apiUrl, false, 'object');
    this.apiResponse(res);
  },
};
</script>

<style lang="scss" scope>
@import './src/assets/scss/components/_vresponsiveimage.scss';
</style>
