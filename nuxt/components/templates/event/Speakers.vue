<template>
  <div class="speakers">
    <div v-if="isSectionText"
      class="speakers__text">
      <v-section-text
          :sectionDetails="convertObject(speakersData)" />
    </div>
    <div class="row">
      <div class="block--full-width">
        <div :class="classModifiers"
          v-if="speakersData && speakersData.speaker_details.length">
          <people-card
            v-for="(item, index) in speakersData.speaker_details"
            :key="index"
            :peopleImage="`${item.image}`"
            :peopleName="item.name"
            :peopleRole="item.designation"
            :cardType="'event'" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Speakers',
  components: {
    PeopleCard: () => import('../people/PeopleCard'),
    VSectionText: () => import('@/shared/components/VSectionText'),
  },
  props: ['speakersData'],
  computed: {
    classModifiers() {
      return [
        'speakers__logo',
      ];
    },
  },
  data() {
    return {
      isSectionText: true,
    };
  },
  methods: {
    convertObject(data) {
      if (data.title && data.title.length > 0) {
        const { title, description } = data;
        const res = {
          label: title,
          description,
        };
        this.isSectionText = true;
        return res;
      }
      this.isSectionText = false;
      return '';
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_speakers.scss';
</style>
