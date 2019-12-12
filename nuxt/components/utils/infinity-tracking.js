export default function setup(locale, icttCode) {
  const ictt = icttCode;
  if (!ictt || !Array.isArray(ictt)) {
    return;
  }
  switch (locale) {
    case 'en-us':
      ictt[0][1] = 60;
      ictt[1][1][0] = 42;
      break;
    case 'en-za':
      ictt[0][1] = 972;
      ictt[1][1][0] = 13549;
      break;
    case 'es-es':
      ictt[0][1] = 2287;
      ictt[1][1][0] = 28321;
      break;
    case 'en-es':
      ictt[0][1] = 2287;
      ictt[1][1][0] = 28321;
      break;
    default:
      ictt[0][1] = 2;
      ictt[1][1][0] = 1;
      break;
  }
}
