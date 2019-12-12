<template>
  <div class="base">
    <!-- Title section -->
    <v-title :title-type="'articleDetail'" :titleDetail="titleDetail"/>
    <!--News article video section -->
    <spotlight v-if="videoDtl.imageId" :videoDtl="videoDtl" :align="'top'" :bgAlign="'left'" />
    <!-- Article detail section -->
    <v-article-detail :articleContent="content" />
    <!-- Share Article section -->
    <v-share-article />
    <!-- All Next section -->
    <v-article-list v-if="article" theme="light" sub-theme="beta" :articleName="articleCategory"
    :article="article" :cardType="cardType"/>
    <!-- Contact section -->
    <div class="bg--action-gamma">
      <v-contact :contactDetails="contactVal" />
    </div>
  </div>
</template>

<script>

export default {
  name: 'VBaseArticle',
  components: {
    VTitle: () => import('../VTitle'),
    VArticleDetail: () => import('./VArticleDetail'),
    VArticleList: () => import('./VArticleList'),
    VContact: () => import('../VContact'),
    VShareArticle: () => import('./VShareArticle'),
    Spotlight: () => import('../video/Spotlight'),
  },
  data() {
    return {
      contactVal: {
        contact_text: '',
        contact_button_text: '',
      },
      article: null,
      titleDetail: {},
      videoDtl: {
        playerId: '',
        youtube_video_id: '',
        imageId: null,
      },
      content: '',
      articleCategory: '',
      structuredData: {},
    };
  },
  props: {
    cardType: {
      type: String,
      required: true,
    },
    articleName: {
      type: String,
    },
  },
  async created() {
    let apiUrl = `wp/v2/${this.cardType}?status=publish&per_page=1&slug=${this.$route.params.id}`;
    let res = {};
    if (this.$getQueryParam('preview_id')) {
      apiUrl = `wp/v2/${this.cardType}/${this.$getQueryParam('preview_id')}`;
      res = await this.$apiCall(apiUrl, true);
    } else {
      res = await this.$apiCall(apiUrl);
    }
    this.apiResponse(res);
  },
  methods: {
    async apiResponse(response) {
      this.article = response;
      const {
        title: {
          rendered: titleName,
        },
        content: {
          rendered,
        },
        acf: {
          default_footer_contact_id: contactData,
          footer_contact_us: contacts,
          youtube_video_id: videoId,
          subtitle_text: subTitle,
          link_text: linkText,
          link_url: linkUrl,
          featured_media: imageId,
          author_information: {
            author_name: name,
            author_role: role,
          },
        },
        date,
        news_categories: newsCategories,
      } = this.article;
      this.content = rendered;
      this.titleDetail = {
        title: titleName,
        subTitle,
        linkText,
        linkUrl,
        date,
        sectionName: newsCategories.join(' | '),
        author: {
          name,
          role,
        },
      };
      this.videoDtl = {
        playerId: `player_${response.id}`,
        youtube_video_id: videoId,
        imageId,
      };
      if (contacts) {
        const [{ acf: contact }] = contacts;
        this.contactVal = contact;
      } else {
        const { acf: contactDeatil } = contactData;
        this.contactVal = contactDeatil;
      }
      const allLable = 'ALL ';
      this.articleCategory = newsCategories && newsCategories.length
        ? allLable.concat(newsCategories[0]) : allLable;
      if (response && response.yoast_meta) {
        this.$addSeoTag(response.yoast_meta);
      }
      if (response) {
        const imageSize = await this.$getImageSize(response.acf.featured_media_url);
        const logoUrl = response.acf.jellyfish_logo;
        const logSize = await this.$getImageSize(response.acf.jellyfish_logo);
        this.structuredData = {
          '@context': 'https://schema.org',
          '@type': 'Article',
          mainEntityOfPage: {
            '@type': 'WebPage',
            '@id': window.location.href.toLowerCase(),
          },
          headline: this.titleDetail.title,
          description: this.titleDetail.subTitle.replace(/<\/?[^>]+(>|$)/g, ''),
          image: {
            '@type': 'ImageObject',
            url: response.acf.featured_media_url,
            width: imageSize.width,
            height: imageSize.height,
          },
          author: {
            '@type': 'Person',
            name: this.titleDetail.author.name,
          },
          publisher: {
            '@type': 'Organization',
            name: 'Jellyfish',
            logo: {
              '@type': 'ImageObject',
              url: logoUrl,
              width: logSize.width,
              height: logSize.height,
            },
          },
          datePublished: response.date,
          dateModified: response.modified,
        };
        this.$addSeoTag(this.structuredData, 'ld+json');
      }
      // To add allpages data layer into gtm for news-insights page
      const layer = {
        event: 'allPages',
        page: {
          author: this.titleDetail.author.name,
          category1: 'News Insights',
          category2: this.$route.params.id,
          contentType: newsCategories.join(' | '),
          contentWordCount: this.content ? this.content.length : 0,
          type: 'content',
          articleTitle: this.titleDetail.title,
        },
      };
      this.$gtm(layer);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
