export default async function setup(apiUrl, preview, type) {
  let token = this.$storageSupport ? JSON.parse(sessionStorage.getItem('Token')) : false;
  if (!token && preview) {
    token = await this.$getToken();
    sessionStorage.setItem('Token', JSON.stringify(token));
  }
  const config = preview ? token : {};
  return new Promise((resolve) => {
    this.$http
      .get(apiUrl, config)
      .then((response) => {
        const data = preview || type === 'object' ? response : response[0];
        resolve(data);
      });
  });
}
