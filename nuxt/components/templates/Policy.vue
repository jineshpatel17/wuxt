<template>
  <div class="policy">
    <!-- Top section -->
    <div class="bg-dark--beta">
      <div class="row">
        <div class="block--full-width">
          <div class="policy__head">
            <h1 class="policy__title">{{ policyDetail.title }}</h1>
            <div class="policy__link-list">
              <div class="policy__link-head">{{ policyDetail.contentText }}</div>
              <ol class="policy__link-container" v-if="policyDetail.policies &&
                policyDetail.policies.length > 0" v-scroll-spy-link>
                <li class="policy__links"
                  v-for="(item,index) in policyDetail.policies" :key="index">
                  <!-- <a class="policy__link" href="javascript:;">{{ item.title }}</a> -->

                  <a class="policy__link
                            link--slide
                            link--slide-blue-solid
                            background-slide--ltr
                            animation-none"
                            href="javascript:;"
                            @mouseover="allowAnimation($event)">
                      <span class="link--slide__text">{{ item.title }}</span>
                  </a>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Body section section -->
    <div class="bg--light-alpha">
      <div class="policy__details">
        <div class="policy__detail-container">
          <div class="row">
            <div class="block--full-width" v-if="policyDetail.policies &&
              policyDetail.policies.length > 0" v-scroll-spy>
              <div class="policy__section"
                v-for="(item,index) in policyDetail.policies" :key="index"
                :id="`policy_${index}`">
                <div class="policy__section-counter">
                  {{ countValue(index + 1, policyDetail.policies.length) }}
                </div>
                <div class="policy__header">{{ item.title }}</div>
                <div class="policy__copy" :inner-html.prop="item.description"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Policy',
  props: {
    policyDetail: {
      type: Object,
    },
  },
  methods: {
    addDigit(length) {
      const digit = length < 10 ? '0' : '';
      return digit;
    },
    countValue(index, length) {
      return `${this.addDigit(index)}${index} / ${this.addDigit(length)}${length}`;
    },
    allowAnimation(event) {
      event.currentTarget.classList.remove('animation-none');
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_policy.scss';
</style>
