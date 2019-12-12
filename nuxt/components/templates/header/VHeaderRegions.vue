<template>
  <div class="row">
    <div class="block--full-width">
      <div class="header-region" v-if="regionsDetail && regionsDetail.acf">
        <!-- Repeat below section -->
        <div class="header-region__section"
          v-for="(region, index) in regionsDetail.acf.region_selector_menu"
          :key="index">
          <div class="header-region__continent">{{ region.region_name }}</div>
          <div class="header-region__box" v-for="(region, r) in region.country"
              :key="r">
            <a :href="`/${region.acf.sub_site[0].locale}/`"
              @mouseover="allowAnimation($event)"
              class="header-region__country
              link--slide
              link--slide-blue-solid
              background-slide--ltr
              animation-none">
              <span class="link--slide__text">
                {{ region.acf.region_name }}
              </span>

              <v-icon-base
                width="6"
                height="12"
                class="svg--chevron-right"
                icon-name="chevron-right"
                view-box="0 0 6 12">
                <icon-chevron-right />
              </v-icon-base>
            </a>
            <!-- As of now EN | ES hide for this phase on next phase need to show -->
            <div class="header-region__languages">
              <a v-for="(subSite, i) in region.acf.sub_site" :key="i"
                :href="`/${subSite.locale}`"
                class="header-region__language">{{ subSite.code }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VHeaderRegions',
  components: {
    VIconBase: () => import('@/components/templates/VIconBase'),
    IconChevronRight: () => import('@/components/templates/icons/IconChevronRight'),
  },
  props: {
    regionsDetail: {
      type: Object,
    },
  },
  methods: {
    allowAnimation(event) {
      event.currentTarget.classList.remove('animation-none');
      event.currentTarget.classList.add('animation-running');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/scss/components/_vheaderregions.scss';
</style>
