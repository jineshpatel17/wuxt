<template>
  <div class="row">
    <div class="block--full-width">
      <div class="navigation">
        <div class="navigation__top" v-if="navigationDetail">
          <div class="navigation__section" v-for="(item, index) in navigationDetail.list"
            :key="index">
            <div @click="gtmNav(item.url, item)">
              <router-link :to="regionUrl(item.url)" class="navigation__header">
                {{ item.post_title }}
                <v-icon-base
                  width="6"
                  height="12"
                  class="svg--chevron-right"
                  icon-name="chevron-right"
                  view-box="0 0 6 12">
                  <icon-chevron-right />
                </v-icon-base>
              </router-link>
            </div>
            <ul class="navigation__link-list">
              <li v-for="(child, childIndex) in item.children"
                :key="childIndex" @click="gtmNav(child.url, item, child)">
                <router-link :to="regionUrl(child.url)"
                @mouseover.native="allowAnimation($event)"
                class="navigation__link
                link--slide
                link--slide-blue-solid
                background-slide--ltr
                animation-none">
                  <span class="link--slide__text">
                    {{ child.post_title }}
                  </span>
                  <v-icon-base
                    width="6"
                    height="12"
                    class="svg--chevron-right"
                    icon-name="chevron-right"
                    view-box="0 0 6 12">
                    <icon-chevron-right />
                  </v-icon-base>
                </router-link>
              </li>
            </ul>
          </div>
        </div>
        <div class="navigation__bottom">
          <div class="navigation__contact">
            <v-button
              :btnText="navigationDetail.contactButtonText ?
                navigationDetail.contactButtonText : ''"
              :btnSize="'large'"
              :btnTheme="pageTheme"
              :btnStyle="'blue'"
              :iconAlign="'chevron-right'"
              :isIcon="true"
              v-on:btnTrigger="seeContactUs(navigationDetail.contactButtonLink,
                navigationDetail)" />
          </div>
          <div class="navigation__social">
            <a target="_blank" rel="noopener noreferrer" aria-label="facebook"
              :href="navigationDetail.facebookLink" class="navigation__social-link"
              @click="gtmSupport(navigationDetail.facebookLink, 'facebook')">
              <v-icon-base width="22" height="22"
                icon-name="facebook" view-box="0 0 22 22">
                <icon-facebook />
              </v-icon-base>
            </a>
            <a target="_blank" rel="noopener noreferrer" aria-label="Twitter"
              :href="navigationDetail.twitterLink" class="navigation__social-link"
              @click="gtmSupport(navigationDetail.twitterLink, 'Twitter')">
              <v-icon-base width="25" height=20 icon-name="twitter" view-box="0 0 25 20">
                <icon-twitter />
              </v-icon-base>
            </a>
            <a target="_blank" rel="noopener noreferrer" aria-label="Instagram"
              :href="navigationDetail.instagramLink" class="navigation__social-link"
              @click="gtmSupport(navigationDetail.instagramLink, 'Instagram')">
              <v-icon-base width="20" height="20" icon-name="Instagram" view-box="0 0 20 20">
                <icon-instagram />
              </v-icon-base>
            </a>
            <a target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
              :href="navigationDetail.linkedinLink" class="navigation__social-link"
              @click="gtmSupport(navigationDetail.linkedinLink, 'LinkedIn')">
              <v-icon-base width="25" height="23"
                icon-name="linkedin" view-box="0 0 430.117 430.118">
                <icon-linked-in />
              </v-icon-base>
            </a>
            <a target="_blank" rel="noopener noreferrer" aria-label="You Tube"
              :href="navigationDetail.youtubeLink" class="navigation__social-link"
              @click="gtmSupport(navigationDetail.youtubeLink, 'You Tube')">
              <v-icon-base width="27" height="27" icon-name="youtube" view-box="0 0 310 310">
                <icon-you-tube />
              </v-icon-base>
            </a>
          </div>
          <div class="navigation__jellyfish-sites" >
            <div v-for="(links,index) in navigationDetail.linkObj" :key="index"
              class="navigation__links">
              <a target="_blank" rel="noopener noreferrer"
                :aria-label="links.linkText === 'Jellyfish Training' ?
                'Jellyfish Training' : 'Jellyfish Dynamix'"
                :title="links.linkText === 'Jellyfish Training' ?
                'Jellyfish Training' : 'Jellyfish Dynamix'"
                :href="links.linkUrl" :class="links.linkText === 'Jellyfish Training' ?
                'navigation__jellyfish-training' : 'navigation__jellyfish-dynamix'"
                @click="gtmSupport(links.linkUrl, links.linkText, 'Jellyfish Sites')">
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
  name: 'VNavigation',
  components: {
    VButton: () => import('@/components/templates/VButton'),
    VIconBase: () => import('@/components/templates/VIconBase'),
    IconFacebook: () => import('@/components/templates/icons/IconFacebook'),
    IconLinkedIn: () => import('@/components/templates/icons/IconLinkedIn'),
    IconYouTube: () => import('@/components/templates/icons/IconYouTube'),
    IconTwitter: () => import('@/components/templates/icons/IconTwitter'),
    IconInstagram: () => import('@/components/templates/icons/IconInstagram'),
    IconChevronRight: () => import('@/components/templates/icons/IconChevronRight'),
  },
  props: {
    pageTheme: {
      type: String,
    },
    navigationDetail: {
      type: Object,
    },
  },
  methods: {
    seeContactUs(url, item) {
      const cUrl = `/${this.$getRegion()}${url}`;
      this.$router.push(cUrl);
      // To add button data layer for gtm
      const layer = {
        event: 'navigation',
        category: 'Button',
        id: item.contactButtonText,
        subcategory: item.contactButtonText,
        type: 'bottom_Navigation',
        url: cUrl,
      };
      this.$gtm(layer);
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
    gtmNav(url, parent, child) {
      // To add top navigation into data layer for gtm
      const cUrl = `/${this.$getRegion()}${url}`;
      let layer = {};
      if (child) {
        layer = {
          event: 'navigation',
          category: parent.post_title,
          id: child.post_title,
          subcategory: child.post_title,
          type: 'top_Navigation',
          url: cUrl,
        };
      } else {
        layer = {
          event: 'navigation',
          category: parent.post_title,
          id: parent.post_title,
          subcategory: '',
          type: 'top_Navigation',
          url: cUrl,
        };
      }
      this.$gtm(layer);
    },
    gtmSupport(url, text, category) {
      this.$emit('externalItemClick', true);
      // To add external link suport into data layer for gtm
      const layer = {
        event: 'navigation',
        category: category || 'Social Share',
        id: text,
        subcategory: text,
        type: 'external_Navigation',
        url,
      };
      this.$gtm(layer);
    },
    allowAnimation(event) {
      event.currentTarget.classList.remove('animation-none');
      event.currentTarget.classList.add('animation-running');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/scss/components/_vnavigation.scss';
</style>
