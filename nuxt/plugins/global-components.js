import Vue from 'vue'
import axios from 'axios'
import App from '@/components/App';
import getToken from '@/components/services/get-token';
import VHeader from '@/components/templates/header/VHeader';
import getRegion from '@/components/services/get-region';
import eventBus from '@/components/utils/event-bus';
import storageSupport from '@/components/utils/storage-support';
import apiCallForSettings from '@/components/services/settings-api-call';
import apiCall from '@/components/services/api-call'
// import utils from '@/assets/utils'



Vue.component('VHeader', VHeader);
/**
 * Import Global Stylesheet
 */
// import '@/assets/styles/scss/app.scss';

// ConfigInterceptors();
Vue.config.productionTip = false
Vue.prototype.$http = axios
Vue.prototype.$getToken = getToken
Vue.prototype.$apiCall = apiCall
Vue.prototype.$getRegion = getRegion
Vue.prototype.$eventBus = eventBus
Vue.prototype.$isStorageSupport = storageSupport
Vue.prototype.$apiCallForSettings = apiCallForSettings
Vue.prototype.$apiCallForSettings = apiCallForSettings

export default ({ app }, inject) => {
  inject('getToken', getToken)
  inject('getRegion', getRegion)
  inject('isStorageSupport', storageSupport)
  inject('apiCallForSettings', apiCallForSettings)
  inject('apiCall', apiCall)
}


/* eslint-disable no-new */
new Vue({
  el: '#app',
  components: { App },
  template: '<App/>',
  head: {
    meta: [
      { charset: 'utf-8' },
      { name: 'keywords', content: '' },
      { name: 'author', content: '' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    ],
    script: [
      { type: 'text/javascript', src: `${document.location.protocol === 'https:' ? 'https:' : 'http:'}//assets.infinity-tracking.net/nas/js/nas.v1.min.js`, async: true, defer: 'defer' },
    ],
  },
});
