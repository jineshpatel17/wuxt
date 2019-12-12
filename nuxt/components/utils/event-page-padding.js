// Text Block padding
export const textBlock = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// Single ImageBlock padding
export const singleImageBlock = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// Video Block padding
export const videoBlock = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// carousel padding
export const carousel = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// schedule padding
export const schedule = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// speaker padding
export const speaker = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// map padding
export const map = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// partners padding
export const partners = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

// text-block padding
export const defaultPadding = {
  mobile: {
    paddingTop: '50px',
    paddingBottom: '50px',
    marginTop: '25px',
    marginBottom: '25px',
  },
  tablet: {
    paddingTop: '70px',
    paddingBottom: '70px',
    marginTop: '35px',
    marginBottom: '35px',
  },
  desktop: {
    paddingTop: '100px',
    paddingBottom: '100px',
    marginTop: '50px',
    marginBottom: '50px',
  },
};

export default function setup(type) {
  switch (type) {
    case 'text_block':
      return textBlock;
    case 'video_block':
      return videoBlock;
    case 'single_image_block':
      return singleImageBlock;
    case 'carousel_block':
      return carousel;
    case 'agenda_block':
      return schedule;
    case 'speaker_block':
      return speaker;
    case 'map_block':
      return map;
    case 'partner_block':
      return partners;
    default:
      return defaultPadding;
  }
}
