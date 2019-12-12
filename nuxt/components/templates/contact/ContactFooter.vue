<template>
  <div class="contact-footer">
    <div class="row">
      <div class="block--full-width" v-if="contactFooter">
        <div class="contact-footer__container">
          <div class="contact-footer__communication">
            <div class="contact-footer__header">{{ contactFooter.message }}</div>
            <div>
              <span class="contact-footer__phone">
                <a :class="regionClass()"
                  @click="$infinityTrackingCode('phone')"
                  :href="`tel:${contactFooter.phone}`">{{ contactFooter.phone }}</a>
              </span>
            </div>
            <a class="contact-footer__link" @click="$infinityTrackingCode('email')"
              :href="`mailto:${contactFooter.email}`">{{ contactFooter.email }}</a>
          </div>
          <div class="contact-footer__reach-office">
            <div class="contact-footer__header">{{ contactFooter.reachToOfficeText }}</div>
            <a class="contact-footer__link" v-if="contactFooter.officeLink"
              :href="regionUrl(contactFooter.officeLink)">
              {{ contactFooter.officeText }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactFooter',
  props: {
    contactFooter: {
      type: Object,
    },
  },
  methods: {
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
    regionClass() {
      const local = this.$getRegion();
      if (local === 'en-us' || local === 'en-za' || local === 'en-es' || local === 'en-gb') {
        return 'number_replace';
      }
      return '';
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_contactfooter.scss';
</style>
