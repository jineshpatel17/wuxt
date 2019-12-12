<template>
  <div :class="progressModifier">
      <div class="progress__bar" v-for="item in progressData" :key="item.index">
        <span class="progress__indicator" :class="item.activeClass"></span>
        <span class="progress__count" v-if="showCount" @click="onSelectProgress(item.index)">
          {{ `0${item.index + 1}` }}
        </span>
      </div>
  </div>
</template>

<script>

export default {
  name: 'VProgressBar',
  props: {
    progressData: {
      type: Array,
    },
    showCount: {
      type: Boolean,
      default: true,
    },
    theme: {
      type: String,
    },
  },
  computed: {
    progressModifier() {
      return [
        'progress',
        this.theme === 'white' ? `progress--theme-${this.theme}` : '',
      ];
    },
  },
  methods: {
    onSelectProgress(index) {
      this.$emit('progressIndex', index);
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vprogressbar.scss';
</style>
