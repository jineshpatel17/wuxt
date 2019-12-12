const uuidv1 = require('uuid/v1');

export default async function setup() {
  const local = this.$isStorageSupport() ? localStorage.getItem('geoiplocation') : false;
  if (local) {
    return local;
  }
  return new Promise((resolve) => {
    const uniq = uuidv1();
    this.$http.get(`wp/v2/geoiplocation?${uniq}`).then((data) => {
      if (this.$isStorageSupport()) {
        localStorage.setItem('geoiplocation', data);
      }
      resolve(data);
    });
  });
}
