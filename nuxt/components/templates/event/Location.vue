<template>
  <div class="location">
    <div v-if="isTopSectionText"
      class="location__section-text--top">
      <v-section-text
          :sectionDetails="convertObject(locationDetail.sectionTextData, 'top')" />
    </div>
    <div class="location__section-map">
      <div class="row" :style="styleJson(locationDetail)">
      <div class="block--full-width">
        <div class="location__map">
          <a :href="getDirectionUrl"
            target="_blank" rel="noopener noreferrer"
            class="location__image-sec">
            <picture>
              <source
                v-for="(image, key) in mapImageUrl()"
                :key="key"
                :srcset="image.url"
                :media="`screen and (max-width: ${image.size}px)`">
                <img
                  :class="[image.loaded]"
                  :src="mapImageUrl()[2].url"
                  :alt="locationDetail.address | unescape |
                  truncate(100, '...')"
                  @load="image.loaded = 'location__image location__image--loaded'"
                />
            </picture>
          </a>
          <div class="location__detail">
            <div class="location__text">
              <span :inner-html.prop="locationDetail.address"></span>
            </div>
            <a :href="getDirectionUrl"
              target="_blank" rel="noopener noreferrer"
              class="location__link">{{ locationDetail.directionText }}</a>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div v-if="isBottomSectionText"
      class="location__section-text--bottom">
      <v-section-text
          :sectionDetails="convertObject(locationDetail.sectionTextData, 'bottom')" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Location',
  components: {
    VSectionText: () => import('@/shared/components/VSectionText'),
  },
  props: {
    locationDetail: {
      type: Object,
    },
  },
  data() {
    return {
      googleDirectionUrl: 'https://www.google.com/maps?saddr=My+Location&daddr=',
      googleImageUrl: 'https://maps.googleapis.com/maps/api/staticmap?center=',
      isTopSectionText: true,
      isBottomSectionText: true,
      image: {
        loaded: 'location__image',
      },
      // Static map Image size
      locationSizes: [{
        type: 'mobile',
        size: 375,
        mapSize: [335, 335],
      }, {
        type: 'tablet',
        size: 768,
        mapSize: [708, 354],
      }, {
        type: 'desktop',
        size: 1600,
        mapSize: [810, 449],
      }],
    };
  },
  computed: {
    getDirectionUrl() {
      const { lattitude, longitude } = this.locationDetail;
      return `${this.googleDirectionUrl}${lattitude},${longitude}`;
    },
  },
  methods: {
    mapImageUrl() {
      const srcset = [];
      const { lattitude, longitude, key } = this.locationDetail;
      for (let i = 0; i < this.locationSizes.length; i += 1) {
        const size = this.locationSizes[i].mapSize;
        const width = Math.round(size[0] / 2);
        const height = Math.round(size[1] / 2);
        const url = `${this.googleImageUrl}${lattitude},${longitude}&zoom=13&markers=size:tiny|color:0x0078ff|label:J|${lattitude
        },${longitude}&scale=2&size=${width}x${height}&key=${key}`;
        srcset.push({
          url,
          size: this.locationSizes[i].size,
        });
      }
      return srcset;
    },
    styleJson(style) {
      const json = {};
      if (style.sectionFontColor) {
        // eslint-disable-next-line dot-notation
        json['color'] = style.sectionFontColor;
      }
      if (style.sectionBgColor) {
        // eslint-disable-next-line dot-notation
        json['backgroundColor'] = style.sectionBgColor;
      }
      return json;
    },
    convertObject(data, type) {
      if (type === 'top') {
        if (data.title && data.title.length > 0) {
          const { title, description } = data;
          const res = {
            label: title,
            description,
          };
          this.isTopSectionText = true;
          return res;
        }
        this.isTopSectionText = false;
        return '';
      }
      if (data.direction_text_title && data.direction_text_title.length > 0) {
        const { direction_text_title: title,
          direction_text_description: description } = data;
        const res = {
          label: title,
          description,
        };
        this.isBottomSectionText = true;
        return res;
      }
      this.isBottomSectionText = false;
      return '';
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_location.scss';
</style>
