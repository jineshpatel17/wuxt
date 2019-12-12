<template>
  <div class="events">
    <div class="events__bg-img">
      <v-lazy-load-image
        v-if="eventDetails.eventBackgroundImageId"
        :id="eventDetails.eventBackgroundImageId" :blockClass="'events__image'" />
    </div>
    <div class="row">
      <div class="block--full-width">
        <div class="events__details">
          <div class="events__card">
            <div class="events__date">{{ eventDetails.eventDate }}</div>
            <span class="events__meta">{{ eventDetails.eventText }}</span>
            <h1 class="events__title">{{ eventDetails.eventTitle }}</h1>
            <div class="events__seperator"></div>
            <p class="events__copy" :inner-html.prop="eventDetails.eventExcerpt">
            </p>
            <router-link :to="regionUrl(`/events/${eventDetails.eventName}`)"
            class="events__link link--slide-blue-underline">
              {{ eventDetails.readArticleText }}
            </router-link>
          </div>
          <v-button v-on:btnTrigger="navigateToEvents(eventDetails.allEventsLink)"
            :btnText="eventDetails.eventsBtnText"
            :btnStyle="'blue'"
            :iconAlign="'chevron-right'"
            :isIcon="true" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Events',
  components: {
    VButton: () => import('@/shared/components/VButton'),
    VLazyLoadImage: () => import('./VLazyLoadImage'),
  },
  props: {
    eventDetails: {
      type: Object,
    },
  },
  methods: {
    navigateToEvents(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
};
</script>

<style lang="scss">
@import "./src/assets/scss/components/_events.scss";
</style>
