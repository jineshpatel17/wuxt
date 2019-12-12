<template>
  <transition name="fade">
    <div :class="spotlightModifiers" @click="onPlay">
      <div class="spotlight__container">
        <!-- Spotlight image section -->
        <div class="spotlight__image"
          :class="imageMVisibleModifier">
          <!-- Includes spotlight image for video preview -->
          <v-lazy-load-image
            :id="videoDtl.imageId" :blockClass="`${imgClass}`"
            :mediaSizes="$imageDimensions('spotlight')"/>
        </div>
        <!-- Spotlight video section -->
        <div class="spotlight__video">
          <v-video :isPlayVideo="isPlayer"
            v-on:videoTrigger="onVideoStateChange($event)"
            :playerId="videoDtl.playerId"
            :videoId="videoDtl.youtube_video_id" />
        </div>
      </div>
      <!-- button left position is container start point -->
      <div class="spotlight__action" v-if="videoDtl.youtube_video_id">
        <div class="row" :class="imageMVisibleModifier">
          <div class="block--full-width">
            <div class="spotlight__button">
              <v-button
                :btnStyle="'white-play'"
                :isIcon="true"
                :iconType="'arrow'"
                :iconAlign="videoType === 'normal' ? 'arrow-right-small' : 'arrow-right'"
                :btnText="''" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Spotlight',
  components: {
    VVideo: () => import('./VVideo'),
    VButton: () => import('../VButton'),
    VLazyLoadImage: () => import('../VLazyLoadImage'),
  },
  data() {
    return {
      isPlayer: false,
      isImageVisible: true,
      blockClass: 'spotlight',
      imgClass: 'spotlight__bg',
    };
  },
  computed: {
    spotlightModifiers() {
      return [
        `${this.blockClass}`,
        `${this.blockClass}--bg-${this.align}`,
        `${this.blockClass}--align-${this.bgAlign}`,
        this.videoDtl.youtube_video_id ? `${this.blockClass}__cursor` : '',
      ];
    },
    imageMVisibleModifier() {
      return [
        !this.isImageVisible ? `${this.blockClass}__image--hidden` : '',
      ];
    },
  },
  props: {
    /**
     * @type {Object}
     */
    videoDtl: {
      type: Object,
      required: true,
    },
    /**
     * @type {String}
     */
    bgAlign: {
      type: String,
      required: true,
    },
    /**
     * @type {String}
     */
    align: {
      type: String,
      required: true,
    },
    videoType: {
      type: String,
    },
  },
  methods: {
    onPlay() {
      if (this.videoDtl.youtube_video_id) {
        this.isPlayer = !this.isPlayer;
      }
    },
    onVideoStateChange(isPlay) {
      this.isImageVisible = !isPlay;
      if (!isPlay) {
        this.isPlayer = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_spotlight.scss';
</style>
