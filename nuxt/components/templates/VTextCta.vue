<template>
  <div class="text-cta">
    <div class="text-cta__section">
      <div class="row">
        <div class="block--full-width">
          <div class="text-cta__container">
            <div class="text-cta__text"
              :inner-html.prop="textCtaDetails.paragraph"></div>

              <span class="btn--slide-wrapper">
                <button
                  class="btn--slide btn--slide-white background-slide--ltr"
                  @click="regionUrl(textCtaDetails.redirectLink)">
                  <span class="btn--slide__text">
                    {{ textCtaDetails.btnText }}
                    <v-icon-base
                      width="6"
                      height="12"
                      class="svg--chevron-right"
                      icon-name="chevron-right"
                      view-box="0 0 6 12">
                      <icon-chevron-right />
                    </v-icon-base>
                  </span>
                </button>
              </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'TextCta',
  components: {
    VButton: () => import('@/shared/components/VButton'),
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronRight: () => import('@/shared/components/icons/IconChevronRight'),
  },
  data() {
    return {
      highlightClass: 'bg-highlight',
    };
  },
  props: {
    textCtaDetails: {
      type: Object,
    },
  },
  methods: {
    regionUrl(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
};
</script>

<style lang="sass" scoped>
@import './src/assets/scss/components/_vtextcta.scss';
</style>
