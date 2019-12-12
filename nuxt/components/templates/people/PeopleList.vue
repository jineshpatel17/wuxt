<template>
  <div class="people-list">
    <div class="row">
      <div class="block--full-width">
        <!-- Filter section starts here -->
        <div class="people-list__form">
          <div class="people-list__fields people-list__fields--search">
            <input
              type="search"
              class="people-list__field"
              placeholder="Search"
              name="searchPeople"
              v-model="peopleSearch"
              v-on:search="filterPeople()" />
          </div>
          <div class="people-list__fields people-list__fields--select">
            <select class="people-list__field" v-model="location">
              <option v-for="(item, index) in regionList"
              :key="index" :value="item">{{ item.displayName }}</option>
            </select>
          </div>
          <div class="people-list__fields">
            <v-button
              :btnDisible="isBtnVisible"
              :btnText="'Search'"
              :btnStyle="'blue'"
              :iconAlign="'chevron-right'"
              :isIcon="true"
              v-on:btnTrigger="filterPeople()"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="people-list__records">
      <div class="row">
        <div class="block--full-width">
          <!-- NJW-999: Hide the people count message -->
          <div class="people-list__count" v-if="false">
            {{ `${this.filteredRecords.length} ${peopleCount}` }}
          </div>
          <div class="people-list__row"  id="people-list-record">
            <people-card
              v-for="(item, index) in filteredRecords"
              :key="index"
              :peopleImage="item.acf.profile_image"
              :peopleName="item.acf.name"
              :peopleRole="item.acf.role" />
          </div>
          <span v-if="isLoading" class="people-list__load-icon"></span>
          <div v-observe-visibility="{
              callback: onscrollLoadData,
              once: false,
              intersection: {
                rootMargin: '200px',
              },
            }">
            <span class="people-list__count"
              v-if="!filteredRecords.length && initLoad"
              >{{ noRecordFound }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'PeopleList',
  components: {
    VButton: () => import('../VButton'),
    PeopleCard: () => import('./PeopleCard'),
  },
  data() {
    return {
      peopleSearch: '',
      filteredRecords: [],
      peopleRecords: [],
      pageCount: 0,
      perScroll: 24,
      pageNo: 1,
      searchRecords: [],
      isCompleted: false,
      isCompletedWithSearch: false,
      regionList: [{
        displayName: 'All Locations',
        name: 'all_locations',
      }],
      location: {
        displayName: 'All Locations',
        name: 'all_locations',
      },
      regionFilter: '',
      initLoad: false,
      blockArray: [],
      isBtnVisible: false,
      totalPaging: 0,
      noRecordFound: 'No Record Found.',
      isLoading: false,
      queryName: '',
      queryRole: '',
    };
  },
  props: {
    peopleCount: String,
  },
  created() {
    this.$http
      .get('wp/v2/regions?per_page=20')
      .then((response) => {
        if (response && response.length > 0) {
          const locations = response.filter(o =>
            o.acf.show_into_people_page && o.acf.show_into_people_page.length);
          const regions = locations.map((o) => {
            const item = {
              displayName: o.acf.region_name,
              name: o.acf.people_import_slug,
            };
            return item;
          });
          this.regionList = [...this.regionList, ...regions];
        }
      });
    this.getPagination();
  },
  methods: {
    getPagination() {
      this.$http
        .get('wp/v2/getPeoplePaging')
        .then((response) => {
          if (response && response.total_paging && response.total_paging > 0) {
            this.totalPaging = response.total_paging;
            this.blockArray = Array.from({ length: response.total_paging }, (v, k) => k + 1);
            const name = this.$getQueryParam('name');
            const role = this.$getQueryParam('role');
            const q = this.$getQueryParam('q');
            if (name || role || q) {
              this.queryName = name || '';
              this.queryRole = role || '';
              this.peopleSearch = q || '';
              this.filterPeople();
            } else {
              this.loadMorePeople();
            }
          }
        });
    },
    filterPeople() {
      this.isLoading = true;
      this.searchRecords = [];
      this.filteredRecords = [];
      this.noRecordFound = '';
      this.pageNo = 1;
      if (this.location && this.location.name && this.location.name !== 'all_locations') {
        this.regionFilter = this.location.name;
      } else {
        this.regionFilter = '';
      }
      this.isCompletedWithSearch = false;
      if (!this.isBtnVisible) {
        this.loadSearchRecords();
      }
    },
    randomBlock() {
      if (this.blockArray && this.blockArray.length) {
        const index = Math.floor((Math.random() * (this.blockArray.length - 1)) + 0);
        let page = 1;
        page = this.blockArray[index];
        this.blockArray.splice(index, 1);
        return page;
      }
      return 0;
    },
    searchPeopleRec() {
      if (!this.peopleSearch.length) {
        this.pageNo = 1;
        this.searchRecords = [];
      }
    },
    loadMorePeople() {
      this.pageCount += 1;
      if (!this.isCompleted) {
        let queryString = '';
        let blockStr = '';
        let isRandomNumber = false;
        if (this.regionFilter && this.regionFilter.length > 0) {
          queryString = `&filter[meta_query][0][key]=regions&filter[meta_query][0][value]=${this.regionFilter}&filter[meta_query][0][compare]=LIKE`;
          blockStr = `&page=${this.pageCount}`;
        } else {
          const randomNumber = this.randomBlock();
          if (randomNumber) {
            blockStr = `&filter[meta_key]=custom_paging&filter[meta_value]=${randomNumber}`;
            isRandomNumber = true;
          } else {
            return;
          }
        }
        this.isBtnVisible = true;
        this.$http
          .get(`wp/v2/people/?status=publish&per_page=${this.perScroll}${blockStr}${queryString}`)
          .then((response) => {
            this.isBtnVisible = false;
            this.isLoading = false;
            this.peopleRecords = this.peopleRecords.concat(response);
            this.filteredRecords = this.peopleRecords;
            if (!this.filteredRecords.length) {
              this.noRecordFound = 'No Record Found.';
            }
            this.initLoad = true;
            if (response.length < this.perScroll && !isRandomNumber) {
              this.isCompleted = true;
            }
          }, () => {
            this.isBtnVisible = false;
            this.isCompleted = true;
            this.isLoading = false;
          });
      }
    },
    onscrollLoadData(isVisible) {
      if (isVisible && (!this.isCompleted || !this.isCompletedWithSearch)) {
        if ((!this.peopleSearch.length && !this.queryRole.length && !this.queryName.length)
          && !this.isCompleted) {
          this.loadMorePeople();
        } else if ((this.peopleSearch.length || this.queryRole.length || this.queryName.length)
          && !this.isCompletedWithSearch) {
          this.pageNo += 1;
          this.loadSearchRecords();
        }
      }
    },
    loadSearchRecords() {
      if (this.peopleSearch.length || this.queryRole.length || this.queryName.length) {
        const searchString = this.peopleSearch.toLowerCase();
        let roleWithName = '';
        let regions = '';
        if (this.regionFilter && this.regionFilter.length > 0) {
          const role = `filter[meta_query][0][0][key]=name&filter[meta_query][0][0][value]=${searchString}`;
          const name = `filter[meta_query][0][1][key]=role&filter[meta_query][0][1][value]=${searchString}`;
          roleWithName = `&filter[meta_query][0][relation]=OR&${role}&filter[meta_query][0][0][compare]=LIKE&${name}&filter[meta_query][0][1][compare]=LIKE`;
          regions = `&filter[meta_query][1][relation]=AND&filter[meta_query][1][0][key]=regions&filter[meta_query][1][0][value]=${this.regionFilter}&filter[meta_query][1][0][compare]=LIKE`;
          const layer = {
            event: 'filter',
            attributes: {
              name: 'region',
              value: this.regionFilter,
            },
          };
          this.$gtm(layer);
        } else {
          roleWithName = this.queryStringUrl();
        }
        if (!this.isCompletedWithSearch) {
          this.isBtnVisible = true;
          this.$http
            .get(`wp/v2/people/?status=publish&per_page=${this.perScroll}&page=${this.pageNo}${roleWithName}${regions}`)
            .then((response) => {
              this.isBtnVisible = false;
              this.isLoading = false;
              this.searchRecords = this.searchRecords.concat(response);
              this.filteredRecords = this.searchRecords;
              if (!this.filteredRecords.length) {
                this.noRecordFound = 'No Record Found.';
              }
              if (response.length < this.perScroll) {
                this.isCompletedWithSearch = true;
              }
            }, () => {
              this.isBtnVisible = false;
              this.isLoading = false;
              this.isCompletedWithSearch = true;
            });
        }
        const layer = {
          event: 'filter',
          attributes: {
            name: 'Search',
            value: searchString,
          },
        };
        this.$gtm(layer);
      } else {
        this.pageNo = 1;
        this.pageCount = 0;
        this.isCompleted = false;
        this.searchRecords = [];
        this.peopleRecords = [];
        this.blockArray = Array.from({ length: this.totalPaging }, (v, k) => k + 1);
        const url = `${window.location.origin}/${this.$getRegion()}/people`;
        history.pushState({}, '', `${url}`);
        this.loadMorePeople();
      }
    },
    queryStringUrl() {
      /*
        Use of this funcation to create API url based on the three parameter
        1) Search result, 2) querystring with role, 3) querystring with name

        In this funcation workf based on the priority
        > 1st priority given to search result
        > 2nd priority given to querysring with role and name
        > 3rd priority given to querysring with role
        > 4th priority given to querysring with name
      */
      const searchString = this.peopleSearch.toLowerCase();
      const url = `${window.location.origin}/${this.$getRegion()}/people`;
      if (searchString.length) {
        history.pushState({}, '', `${url}?q=${searchString}`);
        this.queryRole = '';
        this.queryName = '';
        const name = `filter[meta_query][1][key]=role&filter[meta_query][1][value]=${searchString}`;
        const role = `filter[meta_query][0][key]=name&filter[meta_query][0][value]=${searchString}`;
        return `&filter[meta_query][relation]=OR&${name}&filter[meta_query][0][compare]=LIKE&${role}&filter[meta_query][1][compare]=LIKE`;
      }
      if (this.queryName.length && this.queryRole.length) {
        const qName = `filter[meta_query][1][key]=name&filter[meta_query][1][value]=${this.queryName.toLowerCase()}`;
        const qRole = `filter[meta_query][0][key]=role&filter[meta_query][0][value]=${this.queryRole.toLowerCase()}`;
        return `&filter[meta_query][relation]=AND&${qName}&filter[meta_query][0][compare]=LIKE&${qRole}&filter[meta_query][1][compare]=LIKE`;
      } else if (this.queryRole.length) {
        return `&filter[meta_key]=role&filter[meta_value]=${this.queryRole.toLowerCase()}&filter[meta_compare]=LIKE `;
      } else if (this.queryName.length) {
        return `&filter[meta_key]=name&filter[meta_value]=${this.queryName.toLowerCase()}&filter[meta_compare]=LIKE`;
      }
      history.pushState({}, '', `${url}`);
      this.queryRole = '';
      this.queryName = '';
      const name = `filter[meta_query][1][key]=role&filter[meta_query][1][value]=${searchString}`;
      const role = `filter[meta_query][0][key]=name&filter[meta_query][0][value]=${searchString}`;
      return `&filter[meta_query][relation]=OR&${name}&filter[meta_query][0][compare]=LIKE&${role}&filter[meta_query][1][compare]=LIKE`;
    },
  },
};
</script>

<style lang="scss">
@import "./src/assets/scss/components/_peoplelist.scss";
</style>
