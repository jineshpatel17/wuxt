<template>
  <div class="contact-tabs">
    <div class="row">
      <div class="block--full-width" v-if="contactFormDetail.contactFormTabs
        && contactFormDetail.contactFormTabs.length">
        <div class="contact-tabs__container">
          <div class="contact-tabs__tabs">
            <a href="javascript:;"
              v-for="(tab, index) in contactFormDetail.contactFormTabs"
              :key="index"
              class="contact-tabs__tab"
              :class="activeTab.tab_name === tab.tab_name
              && 'contact-tabs__tab--active'"
              @click="showTab(tab, index)">{{ tab.tab_name }}</a>
          </div>
          <div class="contact-tabs__content"
            v-for="(tab, index) in contactFormDetail.contactFormTabs"
              :key="index">
            <div class="contact-tabs__section"
              v-if="activeTab.tab_name === tab.tab_name">
              <div class="contact-tabs__title"
                v-if="tab.section_title && tab.section_title.length">
                {{ tab.section_title }}
              </div>
              <div class="contact-tabs__copy"
                v-if="tab.section_description && tab.section_description.length"
                :inner-html.prop="replaceRegion(tab.section_description)">
              </div>
            </div>
          </div>
          <contact-form
            v-if="contactFormDetail.formId"
            :id="contactFormDetail.formId"
            :subject="this.activeTab.tab_name" :selectedTab="tabIndex" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ContactTabs',
  components: {
    ContactForm: () => import('@/shared/components/contact/ContactForm'),
  },
  data() {
    return {
      activeTab: {
        tab_name: '',
      },
      tabIndex: 'first',
      tabValues: ['first', 'second', 'third'],
    };
  },
  props: {
    contactFormDetail: {
      type: Object,
    },
  },
  watch: {
    contactFormDetail(value) {
      this.activeTab = value.activeTab;
    },
  },
  methods: {
    showTab(tab, ind) {
      this.tabIndex = this.tabValues[ind];
      this.activeTab = tab;
    },
    replaceRegion(jfInstructions) {
      if (jfInstructions && jfInstructions.includes('##locale##')) {
        return jfInstructions.replace('##locale##', this.$getRegion());
      }
      return jfInstructions;
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_contacttabs.scss';
</style>
