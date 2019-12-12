/* eslint-disable no-underscore-dangle */
export default function setup(type) {
  let trigger = '';
  switch (type) {
    case 'email':
      trigger = 'EMAIL';
      break;
    case 'phone':
      trigger = 'PHONE';
      break;
    default:
      trigger = 'EMAIL';
      break;
  }
  window.parent._ictt.push(['_customTrigger', trigger, { t: 'Enquiry' }]);
}
