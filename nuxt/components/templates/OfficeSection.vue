<template>
  <div class="row">
    <div class="block--full-width">
      <div class="offices" v-if="officesSectionDetails.offices_masonry">
        <!-- <div class="offices__section-text">
          <v-section-text :sectionDetails="sectionDetail" />
        </div> -->
        <div class="offices__row">
          <div class="offices__column">
            <div class="offices__card"
              v-for="(office, index) in
              officesSectionDetails.offices_masonry.column1_offices"
              :key="index">
              <v-lazy-load-image :mediaSizes="size(index, 1)"
              :id="office.acf.image" :blockClass="`${category}__image`" />
              <div class="offices__name">{{ office.acf.region.acf.region_name }}</div>
            </div>
            <div class="offices__card">
              <v-lazy-load-image
              :mediaSizes="portrait"
              :id="getData(officesSectionDetails.offices_masonry.column3_offices[1], 'image')"
              :blockClass="`${category}__image`" />
              <div class="offices__name">{{
                getData(officesSectionDetails.offices_masonry.column3_offices[1], 'name')
              }}</div>
            </div>
          </div>
          <div class="offices__column">
            <div class="offices__card"
              v-for="(office, index) in
              officesSectionDetails.offices_masonry.column2_offices"
              :key="index">
              <v-lazy-load-image :mediaSizes="size(index, 2)"
              :id="office.acf.image" :blockClass="`${category}__image`" />
              <div class="offices__name">{{ office.acf.region.acf.region_name }}</div>
            </div>
            <div class="offices__card">
              <v-lazy-load-image
              :mediaSizes="square"
              :id="getData(officesSectionDetails.offices_masonry.column3_offices[2], 'image')"
              :blockClass="`${category}__image`" />
              <div class="offices__name">{{
                getData(officesSectionDetails.offices_masonry.column3_offices[2], 'name')
              }}</div>
            </div>
          </div>
          <div class="offices__column">
            <div class="offices__card">
              <v-lazy-load-image
              :mediaSizes="landscape"
              :id="getData(officesSectionDetails.offices_masonry.column3_offices[0], 'image')"
              :blockClass="`${category}__image`" />
              <div class="offices__name">{{
                getData(officesSectionDetails.offices_masonry.column3_offices[0], 'name')
              }}</div>
            </div>
            <div class="offices__sub-column">
              <div class="offices__card"
                v-for="(office, index) in
                officesSectionDetails.offices_masonry.column3_offices.slice(1, 3)"
                :key="index">
                <v-lazy-load-image
                :mediaSizes="size(index, 3)"
                :id="office.acf.image" :blockClass="`${category}__image`" />
                <div class="offices__name">{{ office.acf.region.acf.region_name }}</div>
              </div>
            </div>
          </div>
        </div>
        <v-button
          :btnText="officesSectionDetails.our_offices_text"
          :btnStyle="'blue'"
          :iconAlign="'chevron-right'"
          :isIcon="true"
          @btnTrigger="seeOffices($event)"/>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'OfficeSection',
  components: {
    VButton: () => import('@/shared/components/VButton'),
    VSectionText: () => import('./VSectionText'),
    VLazyLoadImage: () => import('./VLazyLoadImage'),
  },
  props: {
    officesSectionDetails: {
      type: Object,
    },
  },
  data() {
    return {
      category: 'offices',
      sectionDetail: {},
      square: this.$imageDimensions('officesSectionInWeAre').squareSize,
      portrait: this.$imageDimensions('officesSectionInWeAre').portraitSize,
      landscape: this.$imageDimensions('officesSectionInWeAre').landscapeSize,
    };
  },
  methods: {
    getData(office, type) {
      if (type === 'image') {
        return office.acf.image;
      }
      return office.acf.region.acf.region_name;
    },
    size(index, column) {
      switch (column) {
        case 1:
          if (index === 0) {
            return this.square;
          }
          return this.portrait;
        case 2:
          return this.portrait;
        case 3:
          if (index === 0) {
            return this.portrait;
          }
          return this.square;
        default:
          return this.portrait;
      }
    },
    removeElement(list) {
      return list.shift();
    },
    seeOffices() {
      this.$router.push(`/${this.$getRegion()}/offices`);
    },
  },
  watch: {
    officesSectionDetails(val) {
      const { section_description: description, section_title: label } = val;
      this.sectionDetail = {
        label,
        description,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
@import "./src/assets/scss/components/_officesection.scss";
</style>
