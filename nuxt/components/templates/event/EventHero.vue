<template>
  <div class="event-hero">
    <div class="event-hero__img-box">
      <v-lazy-load-image
        v-if="eventTitleDetail.eventHeroImage && !eventTitleDetail.eventHeroImageLg"
        :id="eventTitleDetail.eventHeroImage" :blockClass="'event-hero__image'"
        :mediaSizes="$imageDimensions('eventHeroCover')" />
      <picture>
        <source media="(max-width: 375px)" :srcset="eventTitleDetail.eventHeroImagesm" />
        <source media="(max-width: 768px)" :srcset="eventTitleDetail.eventHeroImageMd" />
        <img class="event-hero__image"
          :src="eventTitleDetail.eventHeroImageLg" :alt="eventTitleDetail.title" />
      </picture>
    </div>
    <div class="event-hero__container" v-if="!eventTitleDetail.eventHeroImageLg">
      <v-lazy-load-image
        v-if="eventTitleDetail.eventLogo"
        :id="eventTitleDetail.eventLogo"
        :mediaSizes="$imageDimensions('eventHeroLogo')"
        :blockClass="'event-hero__logo'" />
      <div class="event-hero__title">{{ eventTitleDetail.title }}</div>
      <div class="event-hero__meta">
        <span class="event-hero__date">{{ eventTitleDetail.eventDate }}</span>
        <span class="event-hero__location">{{ eventTitleDetail.eventLocation }}</span>
      </div>
      <div class="event-hero__text"
        :inner-html.prop="eventTitleDetail.eventDescription">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventHero',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  props: {
    eventTitleDetail: {
      type: Object,
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_eventhero.scss';
</style>
