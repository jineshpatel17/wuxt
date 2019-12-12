<template>
  <div class="all-next">
    <div class="row">
      <div class="block--full-width">
        <div class="all-next__container">
          <!-- Left section -->
          <div class="all-next__left">
            <a v-if="allNextDetails.previous"
              :href="allNextDetails.previous.slug" class="all-next__link">
              <v-icon-base
                width="6"
                height="12"
                class="svg--chevron-left"
                icon-name="chevron-left"
                view-box="0 0 6 12">
                <icon-chevron-left />
              </v-icon-base>
              <span class="all-next__text">Previous</span>
              <span class="all-next__title">{{ allNextDetails.previous.title }}</span>
            </a>
          </div>
          <!-- Mid section -->
          <div class="all-next__mid">
            <a class="all-next__work" :href="regionUrl('/work')">
              <span class="icon icon--widget">
                <span class="icon icon--widget__box"
                  v-for="item in 4" :key="item"></span>
              </span>
              <span class="all-next__text">ALL WORK</span>
            </a>
          </div>
          <!-- Right section -->
          <div class="all-next__right">
            <a v-if="allNextDetails.next"
              :href="allNextDetails.next.slug" class="all-next__link">
              <span class="all-next__text">Next</span>
              <v-icon-base
                width="6"
                height="12"
                class="svg--chevron-right"
                icon-name="chevron-right"
                view-box="0 0 6 12">
                <icon-chevron-right />
              </v-icon-base>
              <span class="all-next__title">{{ allNextDetails.next.title }}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AllNext',
  components: {
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronLeft: () => import('@/shared/components/icons/IconChevronLeft'),
    IconChevronRight: () => import('@/shared/components/icons/IconChevronRight'),
  },
  props: {
    allNextDetails: Object,
  },
  methods: {
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
};
</script>

<style lang="sass" scoped>
@import './src/assets/scss/components/_allnext.scss';
</style>
