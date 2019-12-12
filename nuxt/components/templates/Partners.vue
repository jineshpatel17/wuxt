<template>
  <div class="row">
    <div class="block--full-width">
      <div class="partner">
        <div class="partner__container">
          <v-logo :article="partners" :cardType="category" />
          <v-button
            v-if="partners.view_case_studies_text"
            :btnText="partners.view_case_studies_text"
            :btnStyle="'blue'"
            :iconAlign="'chevron-right'" :isIcon="true"
            v-on:btnTrigger="redirectUrl('/work')"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Partners',
  components: {
    VLogo: () => import('@/shared/components/VLogo'),
    VButton: () => import('@/shared/components/VButton'),
  },
  props: {
    partners: {
      type: Object,
    },
  },
  data() {
    return {
      category: 'partners',
    };
  },
  methods: {
    redirectUrl(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
};
</script>

<style lang="scss">
@import './src/assets/scss/components/_partners.scss';
</style>
