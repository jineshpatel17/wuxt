// import * as md5 from 'js-md5';

export default async function setup() {
  const body = new FormData();
  if (this.$getQueryParam('preview_id')) {
    body.set('preview_id', this.$getQueryParam('preview_id'));
    body.set('wpnonce', this.$getQueryParam('wpnonce'));
  } else {
    const ip = await this.$getIp();
    const uniqId = md5(`${ip}${md5('jEllYfIsH')}${Math.floor(Math.random() * (50 - 10)) + 10}`);
    body.set('salt', uniqId);
  }
  return new Promise((resolve) => {
    this.$http.post('/wp/v2/jfauth/generate-token', body).then((data) => {
      const config = {
        headers: {
          Authorization: `Bearer ${data.token}`,
        },
      };
      resolve(config);
    });
  });
}
