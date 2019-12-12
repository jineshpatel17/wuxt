<template>
  <div class="row">
    <div class="box--full-width">
      <div class="progress" :class="stickyClass">
        <div class="progress__bar">
          <span class="progress__indicator" :style="{ height: `${scrollValue}%` }"></span>
        </div>
        <ul class="progress__menu"
          v-scroll-spy-active="activeElement === 0 && {class: 'progress__item--active'}"
          v-scroll-spy-link>
          <li class="progress__item"
            :key="item.name" :class="activeElement === i && 'progress__item--active'"
            v-for="(item, i) in this.menuItems">
            {{ item.isActive }}
            <a class="progress__link"
              @click="onSelectProduct(item.displayName)">{{ item.displayName }}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VProgressIndicator',
  data() {
    return {
      stickyClass: '',
      scrollValue: 0,
      activeElement: 0,
      isClickEvent: false,
      activeSelectedItem: '',
      distanceToTop: '',
    };
  },
  props: ['menuItems', 'stickMode', 'pageIncludes', 'activeItem'],
  watch: {
    isSticky(val) {
      if (val === '--sticky') this.stickyClass = 'progress--sticky';
      else if (val === '--fluid') this.stickyClass = 'progress--fluid';
      else this.stickyClass = '';
    },
    activeItem(val) {
      this.activeElement = val;
      if (!this.isClickEvent) {
        if (this.menuItems && this.menuItems.length && this.menuItems[val]) {
          this.gtmSupport(this.menuItems[val].displayName);
        }
      } else if (this.menuItems && this.menuItems.length &&
        this.menuItems[val] && this.menuItems[val].displayName === this.activeSelectedItem) {
        this.gtmSupport(this.menuItems[val].displayName);
        this.isClickEvent = false;
        this.activeSelectedItem = '';
      }
    },
  },
  created() {
    window.addEventListener('scroll', this.scrollEvent);
  },
  mounted() {
    const progressIndicator = document.querySelector('.progress');
    this.distanceToTop = progressIndicator.getBoundingClientRect().top;
    if (this.menuItems && this.menuItems.length && this.menuItems[0]) {
      this.gtmSupport(this.menuItems[0].displayName);
    }
  },
  methods: {

    scrollEvent() {
      // Get scroll value
      const winScroll =
        document.body.scrollTop || document.documentElement.scrollTop;
      const height =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight;
      this.scrollValue = (winScroll / height) * 100;
      // Get offset of element to make progress sticky
      const getElement = document.getElementById(`${this.pageIncludes}__progress-container`);

      const body = document.body;
      const html = document.documentElement;
      const viewportHeight = window.innerHeight;
      const fullPageHeight = Math.max(body.scrollHeight,
        body.offsetHeight,
        html.clientHeight,
        html.scrollHeight,
        html.offsetHeight);

      let startOffset;
      let endOffset;

      if (document.querySelector('.expertise__page-wrapper') !== null) {
        const footerHeight = document.querySelector('.footer__contact').scrollHeight + document.querySelector('.footer').scrollHeight;
        startOffset = this.distanceToTop - ((viewportHeight / 2) - 198);
        endOffset = fullPageHeight - (Math.max((footerHeight + 396), (viewportHeight * 1.5)));
      } else {
        startOffset = getElement.offsetTop;
        endOffset =
          Math.abs(document.documentElement.clientHeight - startOffset - getElement.clientHeight);
      }

      if ((winScroll > startOffset) && (winScroll < endOffset)) this.stickyClass = 'progress--fade-in';
      else if (winScroll < startOffset) this.stickyClass = 'progress--fade-out';
      else if (winScroll > endOffset) this.stickyClass = 'progress--fluid progress--fade-out';
      else this.stickyClass = 'progress--fade-out';
    },
    onSelectProduct(name) {
      this.isClickEvent = true;
      this.activeSelectedItem = name;
    },
    gtmSupport(name) {
      const url = window.location.href;
      const layer = {
        event: 'virtualPageview',
        vPageName: `${url}/${name}`,
      };
      this.$gtm(layer);
    },
  },
  destroyed() {
    window.removeEventListener('scroll', this.scrollEvent);
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vprogressindicator.scss';
</style>
