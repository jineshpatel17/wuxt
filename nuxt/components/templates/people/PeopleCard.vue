<template>
  <div class="people-card" data-aos="fade-up">
    <div v-if="peopleImage">
      <v-lazy-image
        v-if="cardType !== 'event'"
        :src="peopleImage" :alt="peopleName"
        class="people-card__image">
      </v-lazy-image>
      <v-lazy-load-image
        v-else
        :id="peopleImage"
        :blockClass="'people-card__image'"
        :mediaSizes="$imageDimensions('eventSpeakers')"
      />
    </div>
    <img src="../../../assets/img/no-img-people.jpg" :alt="peopleName"
      class="people-card__image" v-else />
    <div class="people-card__detail">
      <div class="people-card__name">{{ peopleName }}</div>
      <div class="people-card__role">{{ peopleRole }}</div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'PeopleCard',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  props: ['peopleImage', 'peopleName', 'peopleRole', 'cardType'],
};
</script>

<style lang="scss">
@import './src/assets/scss/components/_peoplecard.scss';
</style>
