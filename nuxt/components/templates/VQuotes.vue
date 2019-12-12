<template>
  <div class="row">
    <div class="quotes" v-if="qouteCarouselList.length > 0">
      <section v-for="(quotes, index) in qouteCarouselList" :key="index">
        <div class="quotes__carousel" :class="currSlide === index && 'quotes__carousel--active'">
          <div class="quotes__box quotes__box--first">
            <p class="quotes__text">
              {{ quotes[0].acf.quote_text }}
            </p>
            <div class="quotes__author">
              <div class="quotes__author-name">{{ quotes[0].acf.author }}</div>
              <div class="quotes__author-role">{{ quotes[0].acf.role }}</div>
            </div>
          </div>
          <div class="quotes__box quotes__box--second"
          v-if="quotes.length > 1">
            <p class="quotes__text">
              {{ quotes[1].acf.quote_text }}
            </p>
            <div class="quotes__author">
              <div class="quotes__author-name">{{ quotes[1].acf.author }}</div>
              <div class="quotes__author-role">{{ quotes[1].acf.role }}</div>
            </div>
          </div>
        </div>
      </section>
      <!-- Progress bar section -->
      <v-progress-bar v-if="qouteCarouselList.length > 1" v-on:progressIndex="goToSlide($event)"
        :progressData="progressBarList" />
    </div>
  </div>
</template>
<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
  name: 'VQuotes',
  components: {
    Carousel,
    Slide,
    VProgressBar: () => import('../components/VProgressBar'),
  },
  data() {
    return {
      qouteCarouselList: [],
      progressBarList: [],
      slideIndex: 0,
      quoteInterval: null,
      currSlide: 0,
      progressIndicator: 'progress__indicator',
    };
  },
  props: {
    quoteList: {
      type: Array,
    },
    transitionTime: {
      default: 10,
    },
  },
  watch: {
    quoteList(response) {
      if (response && response.length > 0) {
        const size = 2;
        const fillArray = Array(Math.ceil(response.length / size)).fill();
        const clone = fillArray.map((_, i) => response.slice(i * size, (i * size) + size));
        const progressClone = [];
        for (let j = 0; j < clone.length; j += 1) {
          progressClone.push({ index: j, activeClass: '' });
        }
        this.qouteCarouselList = clone;
        progressClone[0].activeClass = `${this.progressIndicator}--fill-${this.transitionTime}`;
        this.showQuotes();
        this.progressBarList = progressClone;
      }
    },
  },
  methods: {
    onPageChange($event) {
      this.progressBarList.map((item) => {
        const obj = item;
        if (obj.index === $event) {
          obj.activeClass = `${this.progressIndicator}--fill-${this.transitionTime}`;
        } else if (obj.index < $event) {
          obj.activeClass = `${this.progressIndicator}--completed`;
        } else {
          obj.activeClass = '';
        }
        return obj;
      });
    },
    goToSlide(index) {
      clearInterval(this.quoteInterval);
      this.currSlide = index;
      this.onPageChange(this.currSlide);
      this.showQuotes();
      // this.slideIndex = index;
    },

    // Create slider
    showQuotes() {
      this.quoteInterval = setInterval(() => {
        if (this.qouteCarouselList.length) {
          if (this.currSlide >= this.qouteCarouselList.length - 1) this.currSlide = 0;
          else this.currSlide += 1;
          this.onPageChange(this.currSlide);
        }
      }, this.transitionTime * 1000);
    },
  },
  destroyed() {
    clearInterval(this.quoteInterval);
  },
};
</script>
<style lang="scss" scoped>
@import './src/assets/scss/components/_vquotes.scss';
</style>
