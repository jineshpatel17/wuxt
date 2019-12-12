export default function setup(elName, that) {
  const self = that;
  const el = document.querySelector(elName);
  if (!el) {
    return;
  }
  const scroll = (window.scrollY || window.pageYOffset);
  const scrollPos = (window.scrollY || window.pageYOffset) + (window.innerHeight / 2);
  const boundsTop = el.getBoundingClientRect().top + scroll;
  const bounds = {
    top: boundsTop,
    bottom: boundsTop + el.clientHeight,
  };
  if (scrollPos > bounds.top && scrollPos < bounds.bottom) {
    const obj = self.menuItems.find(o => o.class === elName);
    self.activeItem = self.menuItems.indexOf(obj);
  }
}
