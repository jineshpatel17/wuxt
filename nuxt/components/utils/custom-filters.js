import Vue from 'vue';

export default function setup() {
  /**
   * Removes HTML from a string. This should only be used in testing.
   * HTML should be removed from the JSON data before being requested via the API
   * @type {STRING}
   */
  Vue.filter('striptags', (value) => {
    const DIV = document.createElement('DIV');
    DIV.innerHTML = value;
    const TEXT = DIV.textContent || DIV.innerText || '';
    return TEXT;
  });
}
