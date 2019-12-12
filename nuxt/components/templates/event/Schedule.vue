<template>
  <div class="schedule">
    <div v-if="isSectionText"
      class="schedule__text">
      <v-section-text
          :sectionDetails="convertObject(scheduleDetails)" />
    </div>
    <div class="row">
      <div class="block--full-width">
        <div class="schedule__agenda">
          <div class="schedule__list" v-for="(item,index) in scheduleDetails.agenda_details"
            :key="index">
            <div class="schedule__time"> {{item.start_event_time}} - {{item.end_event_time}} </div>
            <div class="schedule__container">
              <div class="schedule__activity" :inner-html.prop="item.sub_event_description"></div>
              <div class="schedule__speakers"
                v-for="(speaker,index) in item.sub_event_speakers" :key="index">
                <div class="schedule__name" v-if="speaker.events_speaker_name.length">
                  {{speaker.events_speaker_name}}
                </div>
                <div v-if="speaker.speakers_designation.length"
                  :class="classModifier(item.sub_event_speakers)">
                  {{ speaker.speakers_designation }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Schedule',
  props: ['scheduleDetails'],
  components: {
    VSectionText: () => import('@/shared/components/VSectionText'),
  },
  data() {
    return {
      isSectionText: true,
    };
  },
  methods: {
    classModifier(list) {
      return [
        'schedule__role',
        list && list.length > 1 ? 'schedule__role--space' : '',
      ];
    },
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

<style lang="sass" scoped>
@import './src/assets/scss/components/_schedule.scss';
</style>
