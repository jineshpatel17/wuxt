<template>
  <div class="row">
    <div class="carousel" v-if="carouselImages.length > 0">
      <carousel easing="ease-out" :perPage="perPageImage"
      :scroll-per-page="false"
      :autoplay="true"
      :paginationEnabled="false"
      :autoplayHoverPause="false"
      :loop="true"
      :autoplayTimeout="3000"
      v-on:pageChange="onPageChange($event)"
      :value="slideIndex">
        <slide v-for="(cItem, index) in carouselImages" :key="index">
          <picture>
            <source
              v-for="(item, key) in getSlides(cItem.image.sizes, cItem.image.mime_type)"
              :key="key"
              :srcset="item.source_url"
              :type="item.mime_type"
              :media="mediaQuery(item, key)">
              <img :alt="cItem.image.alt"
                :src="getSlides(cItem.image.sizes,
                  cItem.image.mime_type)[lastImageSizeKey()].source_url"
                class="carousel__image"/>
          </picture>
          <!-- <v-lazy-image
            :srcset="createSrcSet($imageDimensions('carousel'),
              getSlides(cItem.image.sizes, cItem.image.mime_type), 'srcsets')"
            :sizes="createSrcSet($imageDimensions('carousel'),
              getSlides(cItem.image.sizes, cItem.image.mime_type))"
            :alt="cItem.image.alt" src="" class="carousel__image"/> -->
        </slide>
      </carousel>
      <!-- Progress bar section -->
      <v-progress-bar v-on:progressIndex="goToSlide($event)" :progressData="progressBarList" />
    </div>
  </div>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
  name: 'VCarousel',
  components: {
    Carousel,
    Slide,
    VProgressBar: () => import('../components/VProgressBar'),
  },
  data() {
    return {
      quoteList: [],
      progressBarList: [],
      slideIndex: 0,
    };
  },
  props: {
    carouselImages: {
      type: Array,
    },
    perPageImage: {
      type: Number,
      default: 1,
    },
  },
  watch: {
    carouselImages(images) {
      this.updateProgressBar(images);
    },
  },
  mounted() {
    if (this.carouselImages) {
      this.updateProgressBar(this.carouselImages);
    }
  },
  methods: {
    mediaQuery(image, key) {
      const minOrMax = key === 'preview-desktop' ? 'min-width' : 'max-width';
      const str = `screen and (${minOrMax}: ${image.width}px)`;
      return str;
    },
    lastImageSizeKey() {
      const mediaSizes = this.$imageDimensions('carousel');
      const keys = Object.keys(mediaSizes);
      const last = keys[keys.length - 1];
      if (last) {
        return last;
      }
      return 'ls-1500w';
    },
    getSlides(sizes, mimeType) {
      const createSlides = {};
      const mediaSize = this.$imageDimensions('carousel');
      const slides = Object.keys(sizes).filter(data => !data.includes('width') && !data.includes('height'));
      if (slides && slides.length) {
        for (let i = 0; i < slides.length; i += 1) {
          if (Object.keys(mediaSize).includes(slides[i])) {
            createSlides[slides[i]] = {
              source_url: sizes[slides[i]],
              width: sizes[`${slides[i]}-width`],
              height: sizes[`${slides[i]}-height`],
              mime_type: mimeType,
            };
          }
        }
      }
      return createSlides;
    },
    createSrcSet(mediaSizes, images, type) {
      let mediaQ = '';
      let maxW = '';
      let srcsets = '';
      let mediaSize = '';
      if (mediaSizes) {
        Object.keys(mediaSizes).forEach((key) => {
          if (images[key]) {
            const imageWidth = images[key].width;
            const imageUrl = images[key].source_url;
            maxW = `(max-width: ${mediaSizes[key]}) ${imageWidth}px, `;
            mediaQ += `${maxW}`;
            srcsets = `${srcsets}${imageUrl} ${imageWidth}w,`;
          }
        });
        mediaSize = `${mediaQ}`;
        if (type === 'srcsets') {
          return srcsets;
        }
        return mediaSize;
      }
      return {};
    },
    updateProgressBar(images) {
      if (images && images.length > 0) {
        const progressClone = [];
        for (let j = 0; j < images.length; j += 1) {
          progressClone.push({ index: j, activeClass: '' });
        }
        progressClone[0].activeClass = 'progress__indicator--active';
        this.progressBarList = progressClone;
      }
    },
    onPageChange($event) {
      this.progressBarList.map((item) => {
        const obj = item;
        if (obj.index === $event) {
          obj.activeClass = 'progress__indicator--active';
        } else if (obj.index < $event) {
          obj.activeClass = 'progress__indicator--completed';
        } else {
          obj.activeClass = '';
        }
        return obj;
      });
    },
    goToSlide(index) {
      this.slideIndex = index;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vcarousel.scss';
</style>
