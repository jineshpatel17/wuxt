<template>
  <div class="share">
    <div class="row">
      <div class="block--full-width">
        <div class="share__bg">
          <div class="share__container">
            <span class="share__title">{{shareWith.share_label}}</span>
              <div v-for="(shareObj,index) in shareWith.share_with" :key="index">
                <a @click="openURL(shareObj.url)" rel="noopener noreferrer"
                  class="share__link" target="popup">
                  <v-lazy-image :alt="shareObj.name" :src="shareObj.logo"/>
                </a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VShareArticle',
  data() {
    return {
      shareWith: {
        share_label: '',
        share_with: [],
      },
    };
  },
  async created() {
    const response = await this.$apiCallForSettings('share_settings');
    this.shareWith = {
      share_label: response.share_settings.share_label,
      share_with: response.share_settings.share_with,
    };
  },
  methods: {
    openURL(url) {
      const finalUrl = url + encodeURI(window.location.href.toLowerCase());
      window.open(finalUrl, 'popup', 'width=690,height=540');
    },
  },
};
</script>

<style lang="scss">
@import './src/assets/scss/components/_vsharearticle.scss';
</style>
