<template>
  <div class="row">
    <div class="block--full-width">
      <div class="contact">
        <div class="contact__text" v-html="contactDetails.contact_text"></div>
        <div class="contact__action">
          <v-button
            :btnText="contactDetails.contact_button_text"
            :btnStyle="`white-solid`"
            v-on:btnTrigger="contactDetails.contact_button_text === 'Register' ?
            $scrollTo('register') : seeContactUs('/contact-us')" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VContact',
  components: {
    VButton: () => import('@/shared/components/VButton'),
  },
  props: {
    contactDetails: {
      type: Object,
      required: true,
    },
  },
  methods: {
    seeContactUs(url) {
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vcontact.scss';
</style>
