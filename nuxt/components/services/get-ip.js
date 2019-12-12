export default async function setup() {
  return new Promise((resolve) => {
    this.$http.get('/wp/v2/get-ip').then((data) => {
      resolve(data);
    });
  });
}
