<template>
  <div :class="classList" data-aos="fade-up">
    <!--
    If the type is an event, the image is rendered outside of the main
    card component.
    -->
    <v-lazy-load-image
      v-if="cardType === 'event'"
      :id="article.acf.featured_media"
      :blockClass="`${this.blockClass}__image`"
      :mediaSizes="mediaSizes"
    />
    <div class="card__holder" @click="articleRedirect">
      <!-- :href="`${this.cardType}`/article.slug"> -->
      <!--
      For every non-event card type, the image is rendered within the link
      -->
      <v-lazy-load-image
        v-if="cardType !== 'event'"
        :id="article.acf.featured_media"
        :blockClass="imageModifier"
        :magnifier="'card--clickable'"
        :mediaSizes="mediaSizes"
      />
      <!--
      Card content would be entirely dependant on the card type.

      News, Insights and Events all use the card component, but render
      different levels of information within.
      -->
      <div class="card__content">
        <div class="card__detail">
          <div class="card__meta" v-if="article.type !== 'offices'">
            <span v-if="article.metaText">Latest</span>
            <span class="card__date" v-else>{{ article.date | moment("DD MMMM YYYY") }}</span>
            <span class="card__meta-bullet"></span>
            <span class="card__category" v-if="article.news_categories.length > 0">
              {{ article.news_categories.join(' | ') }}
            </span>
          </div>
          <h2 class="card__title card__title_sep"
          :inner-html.prop="article.title.rendered | unescape |
          truncate(article.metaText ? 200 : 65, '&hellip;')"></h2>
          <!-- <h3 class="card__subtitle">{{ article.acf.post_subtitle | unescape }}</h3> -->
          <div :class="article.type === 'offices' ? 'card__hidden' : 'card__sep'">
            <span class="card__bullet"></span>
          </div>
          <p class="card__text" v-if="article.acf.article_excerpt"
          :inner-html.prop="article.acf.article_excerpt | striptags |
          truncate(article.metaText ? 200 : 120, '&hellip;')">
          </p>
          <div @click="onGetDirection" class="card__readarticle card--align-middle card__link">
            <span class="card__link-text link--slide-blue-underline">
              {{ article.acf.readmore_link_label }}
              <v-icon-base
                v-if="article.type === 'offices'"
                width="6"
                height="12"
                class="svg--chevron-right"
                icon-name="chevron-right"
                view-box="0 0 6 12">
                <icon-chevron-right />
              </v-icon-base>
            </span>
          </div>
        </div>
        <div v-if="article.type === 'offices' && article.acf" class="card__contact">
          <a :href="`mailto:${article.acf.email_id}`" class="card__contact-email"
            @click="$infinityTrackingCode('email')">
            <span class="card__contact-text">{{ article.acf.email_id }}</span>
          </a>
          <a v-if="article.acf.phone_number && article.acf.phone_number.length"
            :href="`tel:${article.acf.phone_number}`"
            class="card__contact-phone"
            @click="$infinityTrackingCode('phone')">
            <span class="card__contact-text">{{ article.acf.phone_number }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VCard',
  components: {
    VLazyLoadImage: () => import('../VLazyLoadImage'),
    VIconBase: () => import('../VIconBase'),
    IconCalender: () => import('../icons/IconCalender'),
    IconChevronRight: () => import('../icons/IconChevronRight'),
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
      blockClass: 'card',
      googleDirectionUrl: 'https://www.google.com/maps?saddr=My+Location&daddr=',
    };
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
      default: 'news',
    },
    item: {
      type: Number,
    },
    theme: {
      type: String,
    },
    mediaSizes: {
      type: Object,
    },
  },
  computed: {
    classList() {
      return [
        /**
         * Block and Element classes are based on props passed in, this gives
         * greater flexibility when creating multi-use components.
         *
         * In the default case, the block is 'Card' and the type is 'News',
         * this gives a Block and Modifier class like 'card--news'.
         */
        `${this.blockClass}`,
        `${this.blockClass}--${this.article.metaText ? 'article-large' : this.cardType}`,
        `${this.blockClass}--${this.theme}`,
        (window.innerWidth < 767 && this.cardType === 'offices' && !this.item) ? `${this.blockClass}--${this.cardType}-first` : '',
        this.cardType !== 'offices' ? `${this.blockClass}__link` : '',
      ];
    },
    imageModifier() {
      return [
        `${this.blockClass}__image`,
        this.article.acf.featured_media ? '' : `${this.blockClass}__image--background`,
      ];
    },
  },
  methods: {
    articleRedirect() {
      if (this.cardType !== 'offices') {
        if (this.article.acf.is_an_external_article) {
          window.open(this.article.acf.external_link, '_blank');
          return;
        }
        this.$router.push(`/${this.$getRegion()}/${this.cardType}/${this.article.slug}`);
      }
    },
    onGetDirection() {
      const { acf: { location: { lat, lng } } } = this.article;
      if (this.cardType === 'offices') {
        window.open(`${this.googleDirectionUrl}${lat},${lng}`, '_blank');
      }
    },
  },
};
</script>

<style lang="scss">
@import './src/assets/scss/components/_vcard.scss';
</style>
