<template>
    <div class="vfilter">
      <div class="row">
        <div class="block--full-width">
          <div class="vfilter__meta">{{ this.vacancyFilter.sectionName }}</div>
          <h1 class="vfilter__title">{{ this.vacancyFilter.title }}</h1>
          <form class="vfilter__form">
            <div class="vfilter__fields">
              <label class="vfilter__label">{{ vacancyFilter.locationText }}</label>
              <select class="vfilter__field"
                @change="onChangeEvent('location')"
                v-model="location">
                <option v-for="country in this.vacancyFilter.locations" :key="country">
                  {{ country }}
                </option>
              </select>
            </div>
            <div class="vfilter__fields">
              <label class="vfilter__label">{{ vacancyFilter.departmentText }}</label>
              <select class="vfilter__field"
                @change="onChangeEvent('department')"
                v-model="department">
                <option v-for="department in this.vacancyFilter.departments" :key="department">
                  {{ department }}
                </option>
              </select>
            </div>
          </form>
          <div class="vfilter__count">
            {{ this.vacancyFilter.message }}
          </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'VacancyFilter',
  props: ['vacancyFilter'],
  data() {
    return {
      location: 'All Locations', /* initial value removed from dropdown as per NJW-793 */
      department: 'All Departments', /* initial value removed from dropdown as per NJW-793 */
    };
  },
  watch: {
    vacancyFilter(val) {
      if (val) {
        const { locale, locations } = val;
        if (locations && locations.length > 0) {
          const existItem = locations.find(o => o.toLowerCase() === locale.toLowerCase());
          this.location = existItem ? locale : 'All Locations';
        }
      }
    },
  },
  methods: {
    onChangeEvent(type) {
      if (type === 'location') {
        this.$emit('onChangeLocation', this.location);
      } else {
        this.$emit('onChangeDepartment', this.department);
      }
    },
  },
};
</script>
<style lang="sass">
@import '../../assets/scss/components/_vacancyfilter.scss';
</style>

