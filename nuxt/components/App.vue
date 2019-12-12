<template>
  <div id="app">
    <!-- Header component -->
    <v-header />
    <!-- Routing based components -->
    <div id="app__main" class="app--init">
      <transition
         name="fade"
         mode="out-in">
         <router-view/>
       </transition>
    </div>
    <!-- <v-footer v-if="isFooterVisible" /> -->
    <!-- <v-cookie /> -->
  </div>
</template>

<script>
// import VCookie from '@/shared/components/VCookie';
import VHeader from '@/components/templates/header/VHeader';
// import VFooter from '@/shared/components/footer/VFooter';

import { locales, defaultLocale } from '@/components/utils/i18n';
import infinityTracking from '@/components/utils/infinity-tracking';

export default {
  name: 'App',
  data() {
    return {
      isFooterVisible: true,
    };
  },
  components: {
    // VCookie,
    VHeader,
    // VFooter,
  },
  created() {
    // this.footerEventHandler();
    // Defualt setting value for animation effect on scroll
    // const aosInit = {
    //   offset: 120,
    //   delay: 300,
    //   duration: 1000,
    //   once: true,
    // };
    // import('aos').then(AOS => AOS.init(aosInit));
  },
  beforeCreate() {
    const local = this.$getRegion();
    const regionNotExist = locales.find(o => o.code === local);
    if (regionNotExist) {
      if (this.$isStorageSupport() && localStorage.getItem('region') !== local) {
        localStorage.setItem('region', local);
      }
      // eslint-disable-next-line no-underscore-dangle
      infinityTracking(local, window._ictt);
      if (!this.$http.defaults.baseURL.includes(local)) {
        this.$http.defaults.baseURL = this.$http.defaults.baseURL.replace('/wp-json/', `/${local}/wp-json/`);
      }
    } else {
      const region = (this.$isStorageSupport() && localStorage.getItem('region')) || defaultLocale;
      window.location = `/${region}/404`;
    }
  },
  methods: {
    footerEventHandler() {
      this.$eventBus.$on('footer-visible', (flag) => {
        this.isFooterVisible = flag;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/scss/pages/_app.scss';
</style>
