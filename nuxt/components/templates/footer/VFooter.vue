<template>
  <footer class="footer bg--light-alpha">
    <div class="footer__container">
      <!-- Add multi-col modifier to Section title for multi-column layout -->
        <!-- Apply footer__col to divide row into 2 equal columns -->
      <div class="footer__logo">
        <router-link aria-label="Jellyfish" :to="regionUrl('/')"
          class="footer__jellyfish-logo">
        </router-link>
        <div class="footer__social">
          <!-- Facebook Icon -->
          <a aria-label="facebook" class="footer__icon"
            target="_blank" rel="noopener noreferrer" :href="socialMediaLinks.facebook_link">
            <v-icon-base width="22" height="22"
              icon-name="facebook" view-box="0 0 22 22">
              <icon-facebook />
            </v-icon-base>
          </a>
          <!-- Twitter icon -->
          <a aria-label="Twitter" class="footer__icon"
            target="_blank" rel="noopener noreferrer" :href="socialMediaLinks.twitter_link">
            <v-icon-base width="25" height=20 icon-name="twitter" view-box="0 0 25 20">
              <icon-twitter />
            </v-icon-base>
          </a>
          <!-- Instagram icon -->
          <a aria-label="Instagram" class="footer__icon"
            target="_blank" rel="noopener noreferrer" :href="socialMediaLinks.instagram_link">
            <v-icon-base width="20" height="20" icon-name="Instagram" view-box="0 0 20 20">
              <icon-instagram />
            </v-icon-base>
          </a>
          <!-- Linkedin Icon -->
          <a aria-label="LinkedIn" class="footer__icon"
            target="_blank" rel="noopener noreferrer" :href="socialMediaLinks.linkedin_link">
            <v-icon-base width="23" height="20"
              icon-name="linkedin" view-box="0 0 430.117 430.118">
              <icon-linked-in />
            </v-icon-base>
          </a>
          <!-- You tube Icon -->
          <a aria-label="You Tube" class="footer__icon"
            target="_blank" rel="noopener noreferrer" :href="socialMediaLinks.youtube_link">
            <v-icon-base width="22" height="22" icon-name="youtube" view-box="0 0 310 310">
              <icon-you-tube />
            </v-icon-base>
          </a>
        </div>
      </div>
      <div class="footer__links">
        <!-- Left side link list -->
        <div class="footer__link-list">
          <span class="footer__link" v-for="item in menuItems" :key="item.ID">
            <a v-if="item.url.includes('//')" target="_blank" rel="noopener noreferrer"
              alt="Jellyfish-Page" :href="item.url">
              {{ item.post_title }}
            </a>
            <router-link v-else alt="Jellyfish-Page"
              :to="regionUrl(item.url)">
              {{ item.post_title }}
            </router-link>
          </span>
        </div>
        <!-- right side link list -->
        <div class="footer__link-list">
          <router-link alt="Cookie Policy" class="footer__link"
            :to="regionUrl(footeritems.cookie_policy_link_url)">
            {{ footeritems.cookie_policy_link_label }}
          </router-link>
          <router-link alt="Privacy Policy" class="footer__link"
            :to="regionUrl(footeritems.privacy_policy_link_url)">
            {{ footeritems.privacy_policy_link_label }}
          </router-link>
          <router-link alt="Modern Slavery Act" class="footer__link"
            :to="regionUrl(footeritems.modern_slavery_link_url)">
            {{ footeritems.modern_slavery_link_label }}
          </router-link>
          <span class="footer__link">
            {{ `${footeritems.copyright_text} 2005-${currentyear}` }}
          </span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>

export default {
  name: 'VFooter',
  components: {
    VIconBase: () => import('../VIconBase'),
    IconFacebook: () => import('../icons/IconFacebook'),
    IconLinkedIn: () => import('../icons/IconLinkedIn'),
    IconYouTube: () => import('../icons/IconYouTube'),
    IconTwitter: () => import('../icons/IconTwitter'),
    IconInstagram: () => import('../icons/IconInstagram'),
  },
  data() {
    return {
      msg: 'Welcome to your Vue.js App',
      socialMediaLinks: {},
      footeritems: {
        copyright_text: '© Jellyfish Group',
        privacy_policy_link_url: '/privacy-policy',
        privacy_policy_link_label: 'Privacy Policy',
        cookie_policy_link_url: '/cookie-policy',
        cookie_policy_link_label: 'Cookie Policy',
        modern_slavery_link_label: 'Modern Slavery Act',
        modern_slavery_link_url: '/modern-slavery-act',
      },
      menuItems: [],
      currentyear: new Date().getFullYear(),
    };
  },
  created() {
    this.apiCalls();
  },
  methods: {
    async apiCalls() {
      const settingRes = await this.$apiCallForSettings('footer_settings,social_pages_link');
      const { footer_settings: footerSettings, social_pages_link: socialLinks } = settingRes;
      this.socialMediaLinks = socialLinks;
      this.footeritems = footerSettings;
      this.$http.get(`wp/v2/menu/?id=${footerSettings.footer_menu}`)
        .then((items) => {
          this.menuItems = items;
        });
    },
    regionUrl(url) {
      return `/${this.$getRegion()}${url}`;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vfooter.scss';
</style>
