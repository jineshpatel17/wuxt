<template>
  <div :id="playerId"></div>
</template>

<script>
export default {
  name: 'VVideo',
  data() {
    return {
      player: {},
    };
  },
  props: {
    /**
     * @type {String}
     */
    playerId: {
      type: String,
      required: true,
    },
    /**
     * @type {String}
     */
    videoId: {
      type: String,
    },
    /**
     * @type {Boolean}
     */
    isPlayVideo: {
      type: Boolean,
      required: true,
    },
  },
  mounted() {},
  watch: {
    isPlayVideo(val) {
      if (val) {
        this.onYouTubeIframeAPIReady();
      }
    },
  },
  methods: {
    stateChanged(event) {
      if (event.data === 0) {
        this.player.destroy();
        this.$emit('videoTrigger', false);
      }
    },
    onYouTubeIframeAPIReady() {
      this.player = new YT.Player(this.playerId, {  // eslint-disable-line
        height: '100%',
        width: '100%',
        videoId: this.videoId,
        frameborder: '0',
        events: {
          onReady: this.onPlayerReady,
          onStateChange: this.onPlayerStateChange,
        },
      });
    },
    onPlayerReady(event) {
      event.target.playVideo();
      this.$emit('videoTrigger', true);
    },
    onPlayerStateChange(event) {
      this.stateChanged(event);
    },
  },
};
</script>
