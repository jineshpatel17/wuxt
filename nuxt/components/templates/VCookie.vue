<template>
  <div class="cookie" v-if="showModal">
    <span class="cookie__text" :inner-html.prop="regionUrl(cookieData.message)"></span>
    <span class="btn--outline-wrapper">
      <a href="javascript:;"
      class="cookie__button btn--slide btn--slide-white background-slide--ltr"
      @click="setCookies">
        <span class="btn--slide__text">
          {{ cookieData.accept_button_label }}
          <v-icon-base
            width="6"
            height="12"
            class="svg--chevron-right"
            icon-name="chevron-right"
            view-box="0 0 6 12">
            <icon-chevron-right />
          </v-icon-base>

        </span>
      </a>
    </span>
  </div>
</template>

<script>

export default {
  name: 'Cookie',
  components: {
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronRight: () => import('@/shared/components/icons/IconChevronRight'),
  },
  data() {
    return {
      showModal: false,
      cookieData: {},
    };
  },
  created() {
    this.apiCalls();
  },
  methods: {
    async apiCalls() {
      const response = await this.$apiCallForSettings('cookie_policy');
      const {
        cookie_policy: cookieData,
      } = response;
      this.cookieData = cookieData;
      this.getStoredCookie();
    },
    setCookies() {
      this.showModal = false;
      const expireDate = this.$moment().add(this.cookieData.cookie_lifetime, 'd').utc().toDate();
      document.cookie = `JFCookie=${this.$getRegion()}; expires=${expireDate}; path=/${this.$getRegion()}`;
      document.cookie = `JFCookie=${this.$getRegion()}; expires=${expireDate}; path=/`;
    },
    getCookieByName(name) {
      if (document.cookie.match(new RegExp(`${name}=([^;]+)`))) {
        return document.cookie.match(new RegExp(`${name}=([^;]+)`));
      }
      return false;
    },
    getStoredCookie() {
      const CookiesData = this.getCookieByName('JFCookie');
      if (CookiesData && CookiesData.length > 0) {
        for (let i = 0; i < CookiesData.length; i += 1) {
          if (CookiesData[i] === this.$getRegion() ||
            CookiesData[i] === `JFCookie=${this.$getRegion()}`) {
            this.showModal = false;
            return;
          }
          this.showModal = true;
        }
      } else {
        this.showModal = true;
      }
    },
    regionUrl(url) {
      if (url) {
        return url.replace('##locale##', this.$getRegion());
      }
      return url;
    },
  },
};
</script>

<style scoped lang="scss">
@import './src/assets/scss/components/_vcookie.scss';
</style>
