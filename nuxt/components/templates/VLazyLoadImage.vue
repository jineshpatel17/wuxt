<template>
  <div class="lazyload-image">
    <picture v-if="this.fullImage && this.fullImage.source_url
      && this.srcsets && this.srcsets.length > 0">
      <source
        v-for="(image, key) in this.srcsets"
        :key="key"
        :srcset="image.imageUrl"
        :type="image.mimeType"
        :media="mediaQuery(image, key)">
        <img
          :src="this.fullImage.source_url"
          :class="[blockClass, magnifier, image.loaded]"
          @load="image.loaded = 'v-lazy-image v-lazy-image-loaded'" />
    </picture>
    <img v-else
      :class="[blockClass, magnifier, image.loaded]"
      :src="this.fullImage.source_url"
      @load="image.loaded = 'v-lazy-image v-lazy-image-loaded'">
  </div>
</template>

<script>
export default {
  name: 'VLazyLoadImage',
  props: ['id', 'blockClass', 'magnifier', 'mediaSizes'],
  data() {
    return {
      images: {},
      fullImage: {
        source_url: '',
      },
      imageID: 0,
      srcsets: [],
      device: document.documentElement.clientWidth < 768 ? 'mobile' : 'large-device',
      image: {
        loaded: 'v-lazy-image',
      },
    };
  },
  methods: {
    mediaQuery(image, key) {
      const MIN_OR_MAX = key === 'full' ? 'min-width' : 'max-width';
      const STRING = `screen and (${MIN_OR_MAX}: ${image.maxWidth})`;
      return STRING;
    },
    apiResponse(response) {
      if (response.media_details.sizes && Object.keys(response.media_details.sizes).length) {
        this.images = response.media_details.sizes;
        this.smallImage = response.media_details.sizes['sq-162w'];
        this.fullImage = response.media_details.sizes.full;
      } else {
        this.fullImage = {
          source_url: response.source_url,
        };
        this.srcsets = [];
        return;
      }
      if (this.mediaSizes) {
        const keys = Object.keys(this.mediaSizes);
        const last = keys[keys.length - 1];
        const srcKey = Object.keys(this.mediaSizes[last])[0];
        if (this.images[srcKey]) {
          this.fullImage = this.images[srcKey];
        }
        keys.forEach((key) => {
          if (this.mediaSizes[key] && typeof this.mediaSizes[key] === 'object') {
            const imgSize = this.mediaSizes[key];
            const imgKeys = Object.keys(imgSize);
            let imageUrl = '';
            let mimeType = 'image/jpeg';
            if (this.images[imgKeys[0]]) {
              mimeType = this.images[imgKeys[0]].mime_type;
            }
            for (let i = 0; i < imgKeys.length; i += 1) {
              if (this.images[imgKeys[i]]) {
                imageUrl += `${this.images[imgKeys[i]].source_url} ${imgSize[imgKeys[i]]}, `;
              }
            }
            if (imageUrl && imageUrl.length > 0) {
              this.srcsets.push({
                imageUrl,
                maxWidth: key,
                mimeType,
              });
            }
          }
        });
      }
      this.$emit('imageLoadedTrigger', true);
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

<style lang="scss" scoped>
@import './src/assets/scss/components/_vlazyloadimage.scss';
</style>
