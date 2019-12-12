<template>
  <div ref="test" class="btn--slide-wrapper">
    <button type="button"
      @mouseover="allowAnimation($event)"
      @click="btnTrigger"
      :disabled="btnDisible"
      :class="btnModifiers">

      <span class="btn--slide__text">
        {{btnText}}

        <v-icon-base
          v-if="iconAlign === 'chevron-right'"
          width="6"
          height="12"
          class="svg--chevron-right"
          icon-name="chevron-right"
          view-box="0 0 6 12">
          <icon-chevron-right />
        </v-icon-base>

        <i v-if="iconType === 'arrow'" class="icon--play">&#9658;</i>
      </span>
    </button>
  </div>
  <!--
      Sample way of using VButton component in any parent component
      <v-button v-on:btnTrigger="btnTrigger1($event)" :btnTheme="`btn--dark`"
      :btnText="`View Case Studies`"/>
    -->
</template>
<script>
export default {
  name: 'VButton',
  components: {
    VIconBase: () => import('@/components/templates/VIconBase'),
    IconChevronRight: () => import('@/components/templates/icons/IconChevronRight'),
  },
  data() {
    return {
      blockClass: 'btn--slide',
      iconClass: 'icon',
    };
  },
  props: {
    btnDisible: {
      type: Boolean,
      default: false,
    },
    btnAnimationDir: {
      type: String,
      default: 'ltr', // Might be light, dark, etc
    },
    btnStyle: {
      type: String,
      detault: 'blue', // See _buttons.scss for options
    },
    btnText: {
      type: String,
      required: true,
    },
    isIcon: {
      type: Boolean,
      default: false,
    },
    iconType: {
      type: String,
      default: 'chevron',
    },
    iconAlign: {
      type: String,
      default: 'arrow-right',
    },
  },
  methods: {
    btnTrigger() {
      this.$emit('btnTrigger', 'vue');
    },
    allowAnimation(event) {
      event.currentTarget.classList.remove('animation-none');
      event.currentTarget.classList.add('animation-running');
    },
  },
  computed: {
    btnModifiers() {
      return [
        `${this.blockClass}`,
        `${this.blockClass}-${this.btnStyle}`,
        `background-slide--${this.btnAnimationDir}`,
        'animation-none',
      ];
    },
    iconModifiers() {
      return [
        `${this.iconClass}`,
        `${this.iconClass}--${this.iconType}`,
        `${this.iconClass}--${this.iconAlign}`,
      ];
    },
  },
};
</script>
<style lang="sass" scoped>
@import '@/assets//styles/scss/components/_buttons.scss';
</style>
