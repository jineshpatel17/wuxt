import { locales } from '../../utils/i18n';

const addMetaTag = (name, content, property) => {
  const existsMeta = document.querySelector(`meta[name='${name}']`);
  let meta;
  if (existsMeta) {
    meta = existsMeta;
  } else {
    meta = document.createElement('meta');
  }
  if (property) {
    const existsMetaP = document.querySelector(`meta[property='${property}']`);
    if (existsMetaP) {
      meta = existsMetaP;
    }
    meta.setAttribute('property', property);
  } else {
    meta.name = name;
  }
  meta.content = content;
  document.getElementsByTagName('head')[0].appendChild(meta);
};

const addLinkTag = (url) => {
  const existsLink = document.querySelector("link[rel='canonical']");
  let linkTag;
  if (existsLink) {
    linkTag = existsLink;
  } else {
    linkTag = document.createElement('link');
  }
  linkTag.rel = 'canonical';
  linkTag.href = url.replace(/\/$/, '');
  document.getElementsByTagName('head')[0].appendChild(linkTag);
};

const addRegionLinkTag = (data) => {
  const existsLink = document.querySelector(`link[hreflang=${data.code}]`);
  let linkTag;
  if (existsLink) {
    linkTag = existsLink;
  } else {
    linkTag = document.createElement('link');
  }
  linkTag.hreflang = data.code;
  linkTag.rel = 'alternate';
  linkTag.href = data.url.replace(/\/$/, '');
  document.getElementsByTagName('head')[0].appendChild(linkTag);
};

const regionLinkTag = (url, regionCode) => {
  for (let i = 0; i < locales.length; i += 1) {
    const data = {
      url: url.replace(regionCode, locales[i].code),
      code: locales[i].code,
    };
    addRegionLinkTag(data);
  }
};

const addScriptTag = (json) => {
  const existsLink = document.querySelector("script[type='application/ld+json']");
  let scrTag;
  if (existsLink) {
    scrTag = existsLink;
  } else {
    scrTag = document.createElement('script');
  }
  scrTag.type = 'application/ld+json';
  scrTag.textContent = JSON.stringify(json);
  document.getElementsByTagName('head')[0].appendChild(scrTag);
};

export default function setup(jsonData, type, routerName) {
  if (type === 'ld+json') {
    if (jsonData && jsonData !== {}) {
      addScriptTag(jsonData);
      return;
    }
  }

  const url = window.location.href.toLowerCase();
  let seoMetaTitle = jsonData.yoast_wpseo_title;
  let hostname = window.location.hostname;

  if (hostname.includes('local')) {
    hostname = 'DEVELOPMENT';
  } else if (hostname.includes('stage')) {
    hostname = 'STAGE';
  } else if (hostname.includes('uat')) {
    hostname = 'UAT';
  } else {
    hostname = 'PRODUCTION';
  }

  if (hostname !== 'PRODUCTION') {
    addMetaTag('robots', 'noindex');
  }

  if (routerName !== 'Homepage') {
    seoMetaTitle = `${jsonData.yoast_wpseo_title}`;
  }

  addLinkTag(url);
  regionLinkTag(url, this.$getRegion());
  addMetaTag('title', seoMetaTitle);
  document.title = seoMetaTitle;
  addMetaTag('description', jsonData.yoast_wpseo_metadesc);
  addMetaTag('twitter:url', url);
  addMetaTag('og:url', url, 'og:url');
  addMetaTag('og:site_name', 'Jellyfish', 'og:site_name');
  addMetaTag('og:type', seoMetaTitle, 'og:type');
  if (jsonData.custom) {
    Object.keys(jsonData.custom).forEach((key) => {
      if (key.includes('_')) {
        const tag = key ? key.replace('_', ':') : key;
        if (tag && tag.length) {
          addMetaTag(tag, jsonData.custom[key], tag.includes('og') ? tag : false);
        }
      }
    });
  }
}
