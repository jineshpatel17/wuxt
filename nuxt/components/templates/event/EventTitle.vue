<template>
  <div class="row">
    <div class="block--full-width">
      <div class="event-title">
        <div class="event-title__section">
          <div class="event-title__section-text">
            <div class="event-title__header-logo" v-if="eventTitleDetail.eventTitleImage">
              <v-lazy-load-image :id="eventTitleDetail.eventTitleImage"
                :blockClass="'event-title__image'" />
            </div>
            <div class="event-title__meta">
              <span class="event-title__date">{{ eventTitleDetail.eventDate }}</span>
              <span class="event-title__bullet"></span>
              <span class="event-title__location">{{ eventTitleDetail.eventLocation }}</span>
            </div>
            <div class="event-title__description event-title__description--lg"
              :inner-html.prop="eventTitleDetail.eventDescription">
            </div>
          </div>
          <div class="event-title__section-image"
            v-if="eventTitleDetail.eventLogo">
            <v-lazy-load-image :id="eventTitleDetail.eventLogo"
              :blockClass="'event-title__image'" />
          </div>
        </div>
        <div class="event-title__description event-title__description--sm"
          :inner-html.prop="eventTitleDetail.eventDescription">
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'EventTitle',
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
@import './src/assets/scss/components/_eventtitle.scss';
</style>
