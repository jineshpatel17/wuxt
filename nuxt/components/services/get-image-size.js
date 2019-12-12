export default async function setup(url) {
  let data = {
    width: 1920,
    height: 960,
  };
  if (!url) {
    return data;
  }
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = (event) => {
      if (event && event.path && event.path.length) {
        data = {
          width: event.path[0].width,
          height: event.path[0].height,
        };
      }
      resolve(data);
    };
    img.src = url;
  });
}
