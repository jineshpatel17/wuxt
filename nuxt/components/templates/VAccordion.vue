<template>
  <div class="accordion" :class="{ mobile: mobileOnly }">

    <button type="button"
      name="button"
      class="accordion__toggle"
      @click="active = !active, getAccPanelHeight()">
      {{ activeBtnText }}
      <span class="icon icon--accordion" :class="activeIcon"></span>
    </button>

    <div class="block--full-width accordion__panel" :class="{ collapsed: !active }">
      <div>
        <slot name="accordionParentContent"></slot>
      </div>


      <div
        v-if="this.parentData.learn_more_link_url"
        class="accordion__cta">
        <a :aria-label="this.parentData.learn_more_link_label"
          :href="this.ctaUrl"
          class="accordion__cta-link link--slide-blue-underline">
          {{ this.parentData.learn_more_link_label }}
          <v-icon-base
            width="6"
            height="12"
            class="svg--chevron-right"
            icon-name="chevron-right"
            view-box="0 0 6 12">
            <icon-chevron-right />
          </v-icon-base>
        </a>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'VAccordion',
  components: {
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronRight: () => import('@/shared/components/icons/IconChevronRight'),
  },
  props: {
    parentData: Object,
    accordionContent: '',
  },
  data() {
    return {
      mobileOnly: true,
      active: false,
      ctaUrl: '',
    };
  },
  methods: {
    getAccPanelHeight() {
      Array.prototype.forEach.call(
        document.querySelectorAll('.accordion__panel'),
        (expanded) => {
          const accordionExpanded = expanded;
          accordionExpanded.style.maxHeight = `${expanded.scrollHeight}px`;
        },
      );
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
  computed: {
    activeIcon() {
      const currIcon = this.active ? 'icon--accordion-open' : 'icon--accordion-closed';
      return currIcon;
    },
    activeBtnText() {
      const currText = this.active ? 'See less' : 'Expand to learn more';
      return currText;
    },
  },
  mounted() {
    this.getAccPanelHeight();
    this.ctaUrl = this.parentData.learn_more_link_url.replace(/^\/|\/$/g, '');
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vaccordion.scss';
</style>
