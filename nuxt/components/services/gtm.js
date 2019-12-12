import { locales } from '../../utils/i18n';

// Find the hostname based on the URL
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

const eventTypes = [
  {
    name: 'allPages',
    key: 'page',
  },
  {
    name: 'formError',
    key: 'attributes',
  },
  {
    name: 'formSubmission',
    key: 'attributes',
  },
  {
    name: 'formStarted',
    key: 'form',
  },
  {
    name: 'formAbandoned',
    key: 'form',
  },
  {
    name: 'allPages',
    key: 'page',
  },
  {
    name: 'filter',
    key: 'attributes',
  },
];

// Page language
const pageLanguage = 'en';

// generic function for the add the data layer
export default function main(data) {
  const eventData = data;
  const local = this.$getRegion();
  const region = locales.find(o => o.code === local);
  const eventType = eventTypes.find(o => o.name === eventData.event);
  if (eventType) {
    eventData[eventType.key].language = pageLanguage;
    eventData[eventType.key].region = region.name;
    eventData[eventType.key].environment = hostname;
    eventData[eventType.key].country = region.countryCodes;
  } else {
    eventData.language = pageLanguage;
    eventData.region = region.name;
    eventData.environment = hostname;
    eventData.country = region.countryCodes;
  }
  window.dataLayer.push(eventData);
}
