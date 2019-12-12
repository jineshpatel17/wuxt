<template>
  <div class="project-results">
    <div class="row">
      <div class="block--full-width">
        <div class="project-results__label">{{this.resultDeatils.title}}</div>
          <div class="project-results__list" v-if="resultDeatils.result.length > 0">
            <div class="project-results__row" v-for="(item,index) in resultDeatils.result"
              :key="index">
              <div class="project-results__title">{{item.topline}}</div>
              <div class="project-results__value">{{item.statistic}}</div>
              <div class="project-results__subtitle">{{item.supplemental}}</div>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProjectResults',
  data() {
    return {
    };
  },
  props: {
    resultDeatils: {
      type: Object,
    },
  },
};
</script>

<style lang="sass" scoped>
@import "./src/assets/scss/components/_projectresults.scss";
</style>
