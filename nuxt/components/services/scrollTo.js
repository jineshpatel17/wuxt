export default function scrollTo(getId) {
  const scrollPos = document.getElementById(getId);
  document.documentElement.scrollTop = scrollPos.offsetTop;
}
