<template>
  <div class="tag-line">
    <div class="row">
      <div class="block--full-width">
        <div class="tag-line__meta"
          v-if="tagLineDetail.brandList && tagLineDetail.brandList.length">
            <span class="tag-line__text"
              v-for="(item,index) in tagLineDetail.brandList" :key="index">
              {{ item.brand_item }}
            </span>
        </div>
        <h1 class="tag-line__title">{{ tagLineDetail.tagline }}</h1>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'TagLine',
  props: {
    tagLineDetail: {
      type: Object,
    },
  },
};
</script>

<style lang="scss" scoped>
@import "./src/assets/scss/components/_tagline.scss";
</style>
