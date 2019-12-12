
// To get region code from the current URL
export default function main() {
  // Regex to get region code from URL ex. /en-us/
  const regex = /^\/([\w]{2})-([\w]{2})(\/)?/g;
  const pageLocale = window.location.pathname.match(regex);
  if (pageLocale && pageLocale.length > 0 && pageLocale[0].length > 0) {
    // Remove '/' from the region code ex. /en-us/ => en-us
    return pageLocale[0].replace(/^\/|\/$/g, '');
  }
  // No value get from the URL use Vue route local using $route
  return '';
}
