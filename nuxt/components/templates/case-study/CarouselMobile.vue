<template>
  <div class="carousel-mobile">
    <div class="row">
      <div class="carousel-mobile__container" v-if="carouselImages.length > 0">
        <carousel :perPage="1.5"
        :scroll-per-page="false"
        :autoplay="true"
        :paginationEnabled="false"
        :autoplayHoverPause="false"
        :loop="true"
        :autoplayTimeout="3000"
        v-on:pageChange="onPageChange($event)"
        :value="slideIndex">
          <slide class="carousel-mobile__slide"
            v-for="(item, index) in carouselImages" :key="index">
            <v-lazy-image :alt="item.image.alt" :src="item.image.sizes.large"
              class="carousel-mobile__image" />
          </slide>
        </carousel>
        <!-- Progress bar section -->
        <v-progress-bar
          v-on:progressIndex="goToSlide($event)"
          :progressData="progressBarList" :theme="'white'" />
      </div>
    </div>
    <div class="carousel-mobile__bg" :style="{ background: bgColor}"></div>
  </div>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
  name: 'CarouselMobile',
  components: {
    Carousel,
    Slide,
    VProgressBar: () => import('../../components/VProgressBar'),
  },
  data() {
    return {
      quoteList: [],
      progressBarList: [],
      slideIndex: 0,
    };
  },
  props: ['carouselImages', 'bgColor', 'magnifier'],
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
@import './src/assets/scss/components/_carouselMobile.scss';
</style>
