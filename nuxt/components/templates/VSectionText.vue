<template>
  <div class="section-text">
    <div class="row">
      <div class="block--full-width">
        <div class="section-text__container">
          <div class="section-text__title">
            <div class="section-text__block">{{ sectionDetails.label }}</div>
          </div>
          <p class="section-text__copy" :inner-html.prop="sectionDetails.description"></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VSectionText',
  data() {
    return {
      label: 'We Are Our Story',
      description: 'We are digital. We are independent.',
    };
  },
  props: {
    sectionDetails: {
      type: Object,
    },
    cardType: {
      type: String,
    },
  },
};
</script>

<style lang="scss" scope>
@import "./src/assets/scss/components/_vsectiontext.scss";
</style>
