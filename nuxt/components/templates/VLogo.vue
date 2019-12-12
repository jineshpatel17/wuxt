<template>
  <div class="row">
    <div class="block--full-width">
      <div :class="blockClass" class="logo">
        <div class="logo__section logo__section--top" v-if="cardType !== 'job'">
          <div class="logo__page-tile">
            <span class="logo__text-block">{{ this.article.section_title }}</span>
          </div>
          <div class="logo__container">
            <div class="logo__about-copy" :inner-html.prop="article.section_description"></div>
            <div class="logo__image" v-if="cardType === 'partners' && article.partner_logo">
              <v-lazy-load-image
                :id="article.partner_logo"
                :blockClass="`${workClass}__image`"
                :mediaSizes="$imageDimensions('singleLogo')"
              />
            </div>
            <div class="logo__text-copy" v-if="cardType === 'partners'"
              :inner-html.prop="article.partner_description"></div>
            <div v-if="cardType === 'academy' || cardType === 'event-detail'">
            <v-button
              v-if="article && article.btnText && article.btnText.length > 0"
              :btnText="article.btnText"
              :btnStyle="'blue'"
              :iconAlign="'chevron-right'" :isIcon="true"
              v-on:btnTrigger="redirectUrl(article.btnUrl)"
              />
              </div>
          </div>
        </div>
        <div class="logo__section logo__section--bottom" v-if="article.images">
          <ul class="logo__logo-list">
            <li :class="listItemClass" v-for="image in article.images" :key="image.id">
              <div class="logo__wrapper">
                <v-lazy-load-image
                  :id="image.acf.logo_image"
                  :blockClass="`${cardType}__image`"
                  :mediaSizes="$imageDimensions('logos')"
                  />
                <div v-if="cardType !== 'work'" class="logo__logo-name">
                  {{ (cardType !== 'work') ? image.post_title : '' }}
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VLogo',
  components: {
    VButton: () => import('@/shared/components/VButton'),
    VLazyLoadImage: () => import('./VLazyLoadImage'),
  },
  data() {
    return {
      /**
       * blockClass is defined as part of the data object so that it can be
       * passed down into child components. It could easily be a fixed value in
       * the template above, but having a fixed value defined here means it
       * would only need to be updated in a single place if we ever changed
       * it or created another component based on this one.
       * @type {Object}
       * @type {String}
       */
      blockClass: `logo__${this.cardType}`,
      workClass: 'work',
      listItemClass: `${this.cardType}__list-item`,
    };
  },
  methods: {
    redirectUrl(url) {
      if (url.includes('//')) {
        window.open(url, '_blank');
        return;
      }
      this.$router.push(`/${this.$getRegion()}${url}`);
    },
  },
  props: {
    /**
     * Articles are passed into the component as a prop, this allows us to do
     * the looping outside of the component and use individual data within.
     * @type {Object}
     */
    article: {
      type: Object,
      required: true,
    },
    /**
     * The cardType property has been set so that it can then be passed down
     * into child components. It could easily be a fixed value in the template
     * above, but having it as a prop allows it to even be passed down the chain
     * from a parent component if needed.
     * @type {Object}
     */
    cardType: {
      type: String,
      default: 'partners',
    },
  },
};
</script>

<style lang="scss" scope>
@import './src/assets/scss/components/_vlogo.scss';
</style>
