<template>
  <svg xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :width="width"
    :height="height"
    :viewBox="viewBox"
    :aria-labelledby="iconName"
    role="presentation"
    xml:space="preserve"
    class="ico"
    x="0px" y="0px">
    <g :fill="iconColor">
      <slot />
    </g>
  </svg>
</template>

<script>

export default {
  props: {
    viewBox: {
      type: String,
      default: '0 0 18 18',
    },
    iconName: {
      type: String,
      default: 'box',
    },
    width: {
      type: [Number, String],
      default: 18,
    },
    height: {
      type: [Number, String],
      default: 18,
    },
    iconColor: {
      type: String,
      default: 'currentColor',
    },
  },
};
</script>

<style scoped>
.ico {
  display: inline-block;
  vertical-align: middle;
}
</style>
