<template>
  <div class="row">
    <div id="paragraph" class="block--full-width paragraph">
      <div v-for="(paragraph, index) in paragraphs" :key="index">
        <div v-if="paragraph.description && (index === 0 || !isReadMore)">
          <div class="paragraph__title">{{ paragraph.title }}</div>
          <ul class="paragraph__description paragraph__description--list"
            v-if="paragraph.description.includes('*')"
            v-html="onSplitOperation(paragraph.description)">
          </ul>
          <p class="paragraph__description"
            v-if="!paragraph.description.includes('*')"
            v-html="paragraph.description">
          </p>
        </div>
      </div>
      <a class="paragraph__option" @click="toggleParagraph">
        {{ this.isReadMore ? 'READ MORE' : 'READ LESS'}}
        <span class="icon icon--numeric" :class="activeIcon"></span>
      </a>
      <div class="paragraph__btn">
        <v-button :btnText="`Apply Now`"
        :btnStyle="'blue'" :iconAlign="'chevron-right'" :isIcon="true"
        v-on:btnTrigger="showJobDetails"/>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  name: 'VParagraph',
  props: {
    paragraphs: {
      type: Array,
    },
    jobData: {
      type: Object,
    },
  },
  components: {
    VButton: () => import('@/shared/components/VButton'),
  },
  data() {
    return {
      isReadMore: true,
    };
  },
  computed: {
    activeIcon() {
      const currIcon = this.isReadMore ? 'icon--numeric-plus' : 'icon--numeric-minus';
      return currIcon;
    },
  },
  methods: {
    onSplitOperation(data) {
      const res = data.split('\t*');
      let str = '';
      res.forEach((item) => {
        if (item && item.trim().length) {
          str += `<li class="paragraph__list">${item}</li>`;
        }
      });
      return str;
    },
    showJobDetails() {
      const baseUrl = this.jobData.jobCountry === 'us' ? 'https://careers-jellyfish-us.icims.com/jobs/' : 'https://careers-jellyfish.icims.com/jobs/';
      const url = `${baseUrl}${this.jobData.jobId}/${this.$route.params.id}/job`;
      window.open(url, '_blank');
    },
    toggleParagraph() {
      const paragraph = document.getElementById('paragraph');
      if (!this.isReadMore) {
        document.documentElement.scrollTop = paragraph.offsetTop; /* NJW-795 */
      }
      this.isReadMore = !this.isReadMore;
    },
  },
};
</script>
<style lang="scss">
@import './src/assets/scss/components/_vparagraph.scss';
</style>
