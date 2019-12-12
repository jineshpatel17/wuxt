export default async function setup(fields) {
  let params = {};
  if (fields && fields.length > 0) {
    params = {
      field_groups: fields,
    };
  }
  return new Promise((resolve) => {
    this.$http.get('wp/v2/settings/jellyfish-settings', { params }).then((data) => {
      resolve(data);
    });
  });
}
