<template>
  <div :class="`${this.blockClass} ${this.blockClass}--${this.colorClass}`"></div>
</template>

<script>

export default {
  name: 'Hero',
  data() {
    return {
      blockClass: 'hero__colors',
      colorClass: this.$randomiseHeroColor.colorClass,
      theme: this.$currentHeroOverlay.theme,
    };
  },
  props: {
    eventDetails: {
      type: Object,
    },
  },
  methods: {
    randomiseHeroBackground() {
      /* Save the current global color, default is blue - see main.js */
      const currentGlobalColor = this.$randomiseHeroColor.colorClass;

      /* Set theme based on global color - for changing text to light or dark */
      if (currentGlobalColor === 'blue' || currentGlobalColor === 'purple' || currentGlobalColor === 'pink' || currentGlobalColor === 'pink-lt') {
        this.$currentHeroOverlay.theme = 'light';
      } else {
        this.$currentHeroOverlay.theme = 'dark';
      }

      /* Save available colors and select one at random, see hero.scss */
      const heroColors = ['blue', 'purple', 'pink', 'pink-lt'];
      let i = Math.floor(Math.random() * heroColors.length);

      /**
       * Update the global color to our random selection,
       * this will be used on the next page visited
       */
      this.$randomiseHeroColor.colorClass = heroColors[i];
      const nextColor = this.$randomiseHeroColor.colorClass;

      const lastItem = heroColors.length - 1;

      /**
       * If the active color is the same as the next one,
       * AND it's the last item in the array, go back to the beginning.
       * OR, if the active color is the same as the next one,
       * use the next color in the array.
       * Otherwise, carry on assigning a random color from our array.
       */
      if (this.colorClass === nextColor && this.colorClass === heroColors[lastItem]) {
        i = 0;
        this.$randomiseHeroColor.colorClass = heroColors[i];
      } else if (this.colorClass === nextColor) {
        this.$randomiseHeroColor.colorClass = heroColors[i + 1];
      }
    },
  },
  mounted() {
    this.randomiseHeroBackground();
  },
};
</script>

<style lang="scss">
@import "./src/assets/scss/components/_hero.scss";
</style>
