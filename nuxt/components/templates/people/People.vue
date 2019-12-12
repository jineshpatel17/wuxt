<template>
  <div class="row">
    <div class="block--full-width">
      <div class="people" v-if="peopleRecords.length > 0">
        <div class="people__progress-bar">
          <!-- Progress bar section -->
          <v-progress-bar :progressData="progressBarList" :showCount="false"/>
          <div class="people__refresh">
            <a class="people__refresh-ico" href="javascript:;" @click="reloadPeolple()"></a>
          </div>
        </div>
        <transition name="slide-fade"
          enter-class="fade-enter"
          enter-active-class="fade-enter--active">
          <div class="people__list">
            <people-card
              v-for="(item, index) in peopleRecords"
              :key="index"
              :peopleImage="item.acf.profile_image"
              :peopleName="item.acf.name"
              :peopleRole="item.acf.role" />
          </div>
        </transition>
        <v-button :btnText="peopleDetails.peopleBtnText"
          :btnStyle="'blue'"
          :iconAlign="'chevron-right'" :isIcon="true"
          v-on:btnTrigger="navigateToPeople('/people')"/>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  name: 'People',
  components: {
    VProgressBar: () => import('../../components/VProgressBar'),
    VButton: () => import('@/shared/components/VButton'),
    PeopleCard: () => import('./PeopleCard'),
  },
  data() {
    return {
      progressBarList: [{
        index: 0,
        activeClass: 'progress__indicator--activated',
      }],
      peopleRecords: [],
      chunkSize: (window.innerWidth < 768) ? 4 : 8,
      pageCount: 0,
      perApiCount: 24,
      totalRecords: [],
      initial: 0,
      peopleInterval: null,
      apiCallInterval: null,
      isAPICall: true,
      isTimerStarted: false,
      activeClass: '',
      blockArray: [],
    };
  },
  props: {
    peopleDetails: {
      type: Object,
    },
  },
  created() {
    window.addEventListener('resize', this.reSizePeopleCard);
    this.getPagination();
  },
  methods: {
    getPeopleResponse() {
      let blockStr = '';
      const randomNumber = this.randomBlock();
      if (randomNumber) {
        blockStr = `&filter[meta_key]=custom_paging&filter[meta_value]=${randomNumber}`;
        this.$http
          .get(`wp/v2/people/?status=publish&per_page=${this.perApiCount}${blockStr}`)
          .then((response) => {
            this.totalRecords = [...this.totalRecords, ...response];
            if (!this.isTimerStarted) {
              this.startTimer();
            }
            if (this.isAPICall) {
              this.getPeopleResponse();
            }
          }, () => {
            this.isAPICall = false;
          });
        this.pageCount += 1;
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
    getPagination() {
      this.$http
        .get('wp/v2/getPeoplePaging')
        .then((response) => {
          if (response && response.total_paging && response.total_paging > 0) {
            this.totalPaging = response.total_paging;
            this.blockArray = Array.from({ length: response.total_paging }, (v, k) => k + 1);
            if (this.isAPICall) {
              this.getPeopleResponse();
            }
          }
        });
    },
    reSizePeopleCard() {
      this.chunkSize = (window.innerWidth < 768) ? 4 : 8;
    },
    startTimer() {
      this.isTimerStarted = true;
      if (this.peopleInterval) {
        clearInterval(this.peopleInterval);
      }
      this.showRecords();
      this.peopleInterval = setInterval(() => {
        setTimeout(() => {
          this.showRecords();
        }, 0);
      }, 12000); /* this number should correspond with FadeInOut animation in peoplecard.scss */
    },
    showRecords() {
      this.progressBarList[0].activeClass = '';
      this.initial += 1;
      if (this.initial === 1) {
        const val = this.totalRecords.slice(0, this.chunkSize);
        if (val.length > 0) {
          this.peopleRecords = val;
        }
      } else {
        const len = (this.initial - 1) * this.chunkSize;
        const nextRecords = this.totalRecords.slice(len, (this.initial * this.chunkSize));
        if (nextRecords.length > 0) {
          this.peopleRecords = nextRecords;
        }
        if (len > this.totalRecords.length) {
          this.initial = 0;
          this.showRecords();
        }
      }
      this.progressBarList[0].activeClass = 'progress__indicator--activated';
    },
    reloadPeolple() {
      this.progressBarList[0].activeClass = '';
      const peopleList = document.querySelector('.people__list');
      peopleList.classList.remove('active');

      setTimeout(() => {
        this.progressBarList[0].activeClass = 'progress__indicator--activated';
        if (this.peopleInterval) {
          clearInterval(this.peopleInterval);
        }
        this.showRecords();
        this.peopleInterval = setInterval(() => {
          setTimeout(() => {
            this.showRecords();
          }, 0);
        }, 12000); /* this number should correspond with FadeInOut animation in peoplecard.scss */
      }, 20);
    },
    navigateToPeople(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
  updated() {
    const peopleList = document.querySelector('.people__list');
    peopleList.classList.add('active');
  },
  destroyed() {
    window.removeEventListener('resize', this.reSizePeopleCard);
    if (this.peopleInterval) {
      clearInterval(this.peopleInterval);
    }
  },
};
</script>
<style lang="scss" scoped>
@import './src/assets/scss/components/_people.scss';
</style>
