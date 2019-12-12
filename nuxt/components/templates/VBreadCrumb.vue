<template>
  <div class="row">
    <div class="block--full-width breadcrumb">
      <router-link :to="regionUrl(this.parentPageDetail.link)"
      @mouseover.native="allowAnimation($event)"
      class="link--slide background-slide--rtl animation-none"
      :class="heroBgCheck === 'true' ? 'link--slide-white-solid' : 'link--slide-blue-solid'">
        <span class="link--slide__text">
          <v-icon-base
            width="6"
            height="12"
            class="svg--chevron-left"
            icon-name="chevron-left"
            view-box="0 0 6 12">
            <icon-chevron-left />
          </v-icon-base>

          {{ this.parentPageDetail.name }}
        </span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VBreadCrumb',
  components: {
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronLeft: () => import('@/shared/components/icons/IconChevronLeft'),
  },
  props: {
    parentPageDetail: {
      type: Object,
    },
  },
  data() {
    return {
      heroBgCheck: this.parentPageDetail.hasHero,
    };
  },
  methods: {
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
    allowAnimation(event) {
      event.currentTarget.classList.remove('animation-none');
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vbreadcrumb.scss';
</style>
