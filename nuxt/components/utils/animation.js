import Vue from 'vue';

export default function setup() {
  Vue.directive('infocus', {
    bind(el, binding) {
      const f = () => {
        const rect = el.getBoundingClientRect();
        const inView = (
          rect.width > 0 &&
          rect.height > 0 &&
          rect.top >= 0 &&
          rect.bottom <= (window.innerHeight * 2 || document.documentElement.clientHeight * 2)
        );
        if (inView) {
          el.classList.add(binding.value);
          window.removeEventListener('scroll', f);
        }
      };
      window.addEventListener('scroll', f);
      f();
    },
  });
}
