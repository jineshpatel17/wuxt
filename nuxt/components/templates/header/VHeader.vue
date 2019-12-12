<template>
  <div class="header" id='header' :class="headerClass">
    <div id="header__navbar" class="header__navbar" :class="navBarClass">
      <div class="header__left">
        <router-link aria-label="Jellyfish" :to="regionUrl('/')" class="header__logo"></router-link>
        <a class="header__dropdown" v-if="hideHeaderText"
          :class="dropDownClass" @click="openMenus('region')">
          <span class="header__region-name">{{ shortNameForRegion(regionName) }}</span>
          <v-icon-base
            width="12"
            height="6"
            class="svg--chevron-down"
            icon-name="chevron-down"
            view-box="0 0 12 6">
            <icon-chevron-down />
          </v-icon-base>
        </a>
      </div>
      <div class="header__right" v-if="hideHeaderText">
        <a class="header__menu" :class="menuClass" @click="openMenus('menu')">
          <span class="header__menu-text">
            {{ navMenu ? headerDetils.closeText : headerDetils.menuText }}
          </span>
          <span class="header__hamburger">
            <span class="header__bar" v-for="(bar, index) in [0, 1, 2]" :key="index"></span>
          </span>
        </a>
      </div>
    </div>
    <!-- Menu opened section -->
    <div class="header__mega-box" @click="hideMenus()">
      <!-- Region selector component -->
      <v-header-regions v-show="regionMenu" :regionsDetail="regionSelectionMenuDetail" />
      <!-- Navigaotion menu component -->
      <v-navigation v-show="navMenu" :pageTheme="'light'"
        @externalItemClick="externalItemClick($event)"
        :navigationDetail="navigationDetail"/>
    </div>
    <!-- Back to top scroll for large pages -->
    <a href="javascript:;" class="header__back2top" v-if="back2Top" @click="onBackToTop()"></a>
  </div>
</template>

<script>
import { locales } from '@/components/utils/i18n';
import utils from '@/assets/utils'


export default {
  name: 'VHeader',
  components: {
    VIconBase: () => import('@/components/templates/VIconBase'),
    IconChevronDown: () => import('@/components/templates/icons/IconChevronDown'),
    VHeaderRegions: () => import('@/components/templates/header/VHeaderRegions'),
    VNavigation: () => import('@/components/templates/header/VNavigation'),
  },
  data() {
    return {
      theme: 'light',
      menuText: 'Menu',
      isScrollUp: false,
      stickyOut: false,
      regionMenu: false,
      navMenu: false,
      navigationDetail: {},
      scrollPos: 0,
      tempScroll: 0,
      regionSelectionMenuDetail: {},
      back2Top: false,
      hideHeaderText: true,
      headerDetils: {
        menuText: 'Menu',
        closeText: 'Close',
      },
      regionName: '',
      // device: document.documentElement.clientWidth < 1230 ? 'mobile' : 'desktop',
      pageHeaderClass: 'header--light',
      isSocialIconClick: true,
    };
  },
  watch: {
    $route() {
      this.regionMenu = false;
      this.navMenu = false;
      const getApp = document.getElementById('app');
      const getBody = document.getElementsByTagName('body')[0];
      getApp.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';
      getBody.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';
    },
  },
  created() {
    // this.headerColorEvent();
    // this.eventPageHandler();
    this.setHeaderRegionName();
    this.apiCalls();
  },
  computed: {
    headerClass() {
      return [
        this.pageHeaderClass,
        (this.regionMenu || this.navMenu) && 'header--open',
      ];
    },
    navBarClass() {
      return [
        this.isScrollUp && 'header__navbar--sticky',
        (this.stickyOut && !this.isScrollUp) && `header__navbar--sticky-out ${this.applyHeaderClass()}`,
      ];
    },
    dropDownClass() {
      return this.regionMenu ? 'header__dropdown--open' : '';
    },
    menuClass() {
      return this.navMenu ? 'header__menu--open' : '';
    },
  },
  methods: {
    async apiCalls() {
      const fields = 'header_settings,social_pages_link,search_engine_optimization';
      const settingRes = await this.$apiCallForSettings(fields);
      const { header_settings: headerSettings,
        social_pages_link: socialMediaLinks,
        search_engine_optimization: searchEngineOptimization } = settingRes;
      const { hreflang: hrefLang } = searchEngineOptimization;
      if (hrefLang && hrefLang.length > 0) {
        this.updateHead(hrefLang);
      }
      this.$http.get(`wp/v2/menu/?id=${headerSettings.header_menu}`)
        .then((menuItems) => {
          const {
            menu_open_text: menuText,
            menu_close_text: closeText,
            contact_button_text: contactButtonText,
            contact_button_link: contactButtonLink,
          } = headerSettings;
          this.headerDetils = {
            menuText,
            closeText,
          };
          const { partners_link: partnersLink } = headerSettings;
          const linkObj = [];
          for (let i = 0; i < partnersLink.length; i += 1) {
            const obj1 = {
              linkText: partnersLink[i].link_text,
              linkUrl: partnersLink[i].link_url,
            };
            linkObj.push(obj1);
          }
          this.navigationDetail = {
            list: this.getNestedChildren(menuItems, '0', null),
            contactButtonText,
            contactButtonLink,
            linkObj,
            twitterLink: socialMediaLinks.twitter_link,
            youtubeLink: socialMediaLinks.youtube_link,
            linkedinLink: socialMediaLinks.linkedin_link,
            facebookLink: socialMediaLinks.facebook_link,
            instagramLink: socialMediaLinks.instagram_link,
          };
        });
      this.$http.get(`wp/v2/region_selector/${headerSettings.region_selector_menu}`)
        .then((response) => {
          if (response) {
            this.regionSelectionMenuDetail = response;
          }
        });
    },
    applyHeaderClass() {
      if (this.tempScroll < 300) return '';
      return 'header__fixed';
    },
    updateHead(hrefLang) {
      hrefLang.forEach((item) => {
        const linkTag = document.createElement('link');
        linkTag.rel = item.link_rel;
        linkTag.hreflang = item.hreflang;
        linkTag.href = item.href;
        document.getElementsByTagName('head')[0].appendChild(linkTag);
      });
    },
    externalItemClick() {
      this.isSocialIconClick = false;
    },
    updateScroll() {
      if (this.navMenu || this.regionMenu) {
        return;
      }
      const mainApp = document.getElementById('app__main');
      const scrollVal = window.scrollY;
      const navBarHeight = document.getElementById('header__navbar').clientHeight;
      this.scrollPos = scrollVal;
      if (this.tempScroll < this.scrollPos && scrollVal > navBarHeight + navBarHeight) {
        this.isScrollUp = false;
        mainApp.className = '';
      } else if (this.tempScroll > this.scrollPos && !(scrollVal <= navBarHeight)) {
        this.isScrollUp = true;
        mainApp.className = 'app';
      } else if (scrollVal <= 1) {
        this.isScrollUp = false;
        mainApp.className = 'app--init';
      }
      this.tempScroll = this.scrollPos;
      if (this.tempScroll > 900) this.back2Top = true;
      else this.back2Top = false;
      if (this.tempScroll > 90) this.stickyOut = true;
      else this.stickyOut = false;
    },
    shortNameForRegion(region) {
      if (region && region.length > 0 && this.device === 'mobile') {
        const char = region.split(' ');
        if (char && char.length > 1) {
          const shortName = char.map((o) => {
            if (o && o.length > 0) {
              return o.charAt(0);
            }
            return o;
          });
          return shortName.join('');
        }
      }
      return region;
    },
    openMenus(val) {
      if (val === 'region') {
        this.navMenu = false;
        this.regionMenu = !this.regionMenu;
      } else if (val === 'menu') {
        this.regionMenu = false;
        this.navMenu = !this.navMenu;
      }
      const getApp = document.getElementById('app');
      const getBody = document.getElementsByTagName('body')[0];
      getApp.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';
      getBody.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';

      /* Reset animated link classes so CSS animations don't run each time menu is opened */
      const animatedLinks = document.getElementsByClassName('animation-running');
      while (animatedLinks.length) {
        const animatedItem = animatedLinks[0];
        animatedItem.classList.remove('animation-running');
        animatedItem.classList.add('animation-none');
      }
    },
    hideMenus() {
      if (this.isSocialIconClick) {
        this.navMenu = false;
        this.regionMenu = false;
        const getApp = document.getElementById('app');
        const getBody = document.getElementsByTagName('body')[0];
        getApp.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';
        getBody.style = (this.navMenu || this.regionMenu) ? 'overflow: hidden' : 'overflow: auto';

        /* Reset animated link classes so CSS animations don't run each time menu is opened */
        const animatedLinks = document.getElementsByClassName('animation-running');
        while (animatedLinks.length) {
          const animatedItem = animatedLinks[0];
          animatedItem.classList.remove('animation-running');
          animatedItem.classList.add('animation-none');
        }
      } else {
        this.isSocialIconClick = true;
      }
    },
    getNestedChildren(arr, parentID1, parentID2) {
      const children = [];
      for (let i = 0; i < arr.length; i += 1) {
        if (arr[i].menu_item_parent === parentID1 || arr[i].menu_item_parent === parentID2) {
          const grandChildren = this.getNestedChildren(arr, `${arr[i].ID}`);
          if (grandChildren.length) {
            // eslint-disable-next-line no-param-reassign
            arr[i].children = grandChildren;
          }
          children.push(arr[i]);
        }
      }
      return children;
    },
    regionUrl(url) {
      return `/${this.region}${url}`;
    },
    backToTop(scrollDuration) {
      const scrollStep = -window.scrollY / (scrollDuration / 15);
      const scrollInterval = setInterval(() => {
        if (window.scrollY !== 0) {
          window.scrollBy(0, scrollStep);
        } else clearInterval(scrollInterval);
      }, 15);
    },
    eventPageHandler() {
      const getHtml = document.getElementsByTagName('html')[0];
      this.$eventBus.$on('hide-event-header-link', (flag) => {
        if (flag) {
          this.hideHeaderText = false;
          getHtml.style = 'scroll-behavior: smooth';
        } else {
          this.hideHeaderText = true;
          getHtml.style = 'scroll-behavior: auto';
        }
      });
    },
    headerColorEvent() {
      this.$eventBus.$on('page-header-light', (flag) => {
        if (flag) {
          this.pageHeaderClass = 'header--alternate';
        } else {
          this.pageHeaderClass = 'header--light';
        }
      });
    },
    setHeaderRegionName() {
      // console.log('test213')

      
      console.log('testing123', this.region)
      if (this.region) {
        this.regionName = region.name;
        if (this.$isStorageSupport()) {
          localStorage.setItem('country', this.regionName);
        }
      }
    },
    onBackToTop() {
      if (this.hideHeaderText) {
        this.backToTop(1000);
      } else {
        this.$scrollTo('header');
      }
    },
  },
  mounted() {
    window.addEventListener('scroll', this.updateScroll);
    this.region = locales.find(o => o.code === this.$getRegion());
  },
  destroyed() {
    window.removeEventListener('scroll', this.updateScroll);
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/scss/components/_vheader.scss';
</style>
