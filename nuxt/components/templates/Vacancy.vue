<template>
  <div class="row">
    <div class="block--full-width">
      <div class="vacancy">
        <div class="vacancy__section">
          <div class="vacancy__page-tile">
            <span class="vacancy__text-block">{{ vacancyDetails.section_title }}</span>
          </div>
          <div class="vacancy__detail">
            <div class="vacancy__copy" :inner-html.prop="vacancyDetails.section_description"></div>
            <!-- <div class="vacancy__count vacancy__count--large">
              {{ vacancyDetails.jobs_count }}
            </div>
            <div class="vacancy__region">{{ vacancyDetails.vacancies_text }}</div> -->
            <a
              alt="View All"
              class="vacancy__link"
              :href="regionUrl(`/jobs/?locale=${region}`)"
            >{{ vacancyDetails.view_all_vacancies_text }}</a>
            <div class="vacancy__button">
              <v-button
                v-on:btnTrigger="showJobs"
                :btnText="vacancyDetails.vacancyButtonText"
                :btnStyle="'blue'"
                :iconAlign="'chevron-right'"
                :isIcon="true"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Vacancy',
  components: {
    VButton: () => import('@/shared/components/VButton'),
  },
  data() {
    return {
      listOfJobs: [],
      maxVal: '',
      region: 'United Kingdom',
    };
  },
  computed: {
    listItems() {
      function compare(a, b) {
        if (a.locale < b.locale) {
          return -1;
        }
        if (a.locale > b.locale) {
          return 1;
        }
        return 0;
      }
      return this.listOfJobs.slice().sort(compare);
    },
  },
  props: {
    vacancyDetails: {
      type: Object,
      required: true,
    },
  },
  created() {
    Object.keys(this.vacancyDetails.jobs_count).forEach((key) => {
      if (key !== this.region) {
        const obj1 = {
          jobCount: this.vacancyDetails.jobs_count[key],
          locale: key,
        };
        if (obj1.jobCount < 10) {
          obj1.jobCount = 0 + obj1.jobCount;
        }
        this.listOfJobs.push(obj1);
      } else {
        this.maxVal = this.vacancyDetails.jobs_count[key];
      }
    });
  },
  methods: {
    showJobs() {
      this.$router.push(`/${this.$getRegion()}${this.vacancyDetails.vacancyButtonLink}`);
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
};
</script>

<style lang="scss" scope>
@import './src/assets/scss/components/_vacancy.scss';
</style>
