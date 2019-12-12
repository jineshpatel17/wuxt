/*
  Use of this file to create json+LD based on the provided JSON
  Offices and Homepage Json+ld managed
*/
export default function setup(type) {
  let point = {
    '@type': 'ContactPoint',
    contactType: 'customer support',
    name: '',
    telephone: '',
    email: '',
  };

  let postalAddress = {
    '@type': 'place',
    name: '',
    address: {
      '@type': 'postalAddress',
      streetAddress: '',
      addressLocality: '',
      addressRegion: '',
      addressCountry: '',
      postalCode: '',
    },
  };

  const structuredData = {
    '@context': 'https://schema.org/',
    '@type': 'Corporation',
    name: 'Jellyfish',
    ContactPoint: [],
    location: [],
  };

  const homeStructuredDataObj = {
    '@context': 'http://schema.org',
    '@type': 'Corporation',
    name: 'Jellyfish',
    url: 'https://www.jellyfish.com',
    description: 'Your digital partner',
    foundingDate: '1999',
    naics: '451613',
    email: '',
    telephone: '',
    logo: 'https://www.jellyfish.com/jellyfishlogo.jpg',
    founder: {
      '@type': 'Person',
      name: 'Rob Pierre',
      image: 'https://www.jellyfish.com/wp-content/uploads/2019/05/Rob_Pierre.jpg',
      jobTitle: 'CEO',
    },
    subOrganization: [{
      '@type': 'organization',
      name: 'Jellyfish Connect',
      email: 'hello@jellyfishconnect.com',
      telephone: '+44-3331309611',
      url: 'https://www.jellyfishconnect.com',
      subOrganization: [{
        '@type': 'organization',
        name: 'magazine.co.uk',
        url: 'https://www.magazine.co.uk',
      }, {
        '@type': 'organization',
        name: 'uOpen',
        url: 'https://www.uopen.com/',
      }, {
        '@type': 'organization',
        name: 'Pocketmags',
        url: 'https://pocketmags.com',
      }],
    }, {
      '@type': 'organization',
      name: 'Jellyfish Dynamix',
      email: 'partner@jellyfishdynamix.com',
      telephone: '+1-443-453-0022',
      url: 'https://www.jellyfishdynamix.com',
    }],
    department: [{
      '@type': 'Organization',
      name: 'Jellyfish Training',
      contactPoint: [{
        '@type': 'contactPoint',
        contactType: 'sales',
        name: 'Jellyfish Training',
        telephone: '+44-08082504012',
        url: 'https://www.jellyfish.com/en-gb/training/',
      }] },
    ],
    sameAs: [
      'https://www.facebook.com/JellyfishGlobal/',
      'https://www.linkedin.com/company/jellyfish-online-marketing',
      'https://www.youtube.com/user/JellyfishAgency/',
      'https://twitter.com/jellyfishglobal/',
      'https://www.latitudegroup.com/',
    ],
    ContactPoint: [],
    location: [],
  };

  return new Promise((resolve) => {
    this.$http.get('wp/v2/pages?slug=offices&status=publish').then((data) => {
      if (data && data.length && data[0].acf && data[0].acf.offices) {
        const officesList = data[0].acf.offices;
        officesList.forEach((project) => {
          const title = project.post_title;
          const officeName = title ? title.split(',')[0] : title;
          const name = `Jellyfish - ${officeName}`;
          point = {
            '@type': 'ContactPoint',
            contactType: 'customer support',
            name,
            telephone: project.acf.phone_number,
            email: project.acf.email_id,
          };
          if (project.acf.region &&
                project.acf.region.acf.sub_site.length
                && project.acf.region.acf.sub_site.length > 0
                && !homeStructuredDataObj.email.length
                && !homeStructuredDataObj.telephone.length) {
            const exist = project.acf.region.acf.sub_site
              .find(o => o.locale === this.$getRegion());
            if (exist) {
              homeStructuredDataObj.email = project.acf.email_id;
              homeStructuredDataObj.telephone = project.acf.phone_number;
            }
          }
          postalAddress = {
            '@type': 'place',
            name: '',
            address: {
              '@type': 'postalAddress',
              streetAddress: '',
              addressLocality: '',
              addressRegion: '',
              addressCountry: '',
              postalCode: '',
            },
          };
          if (project.acf.json_ld_fields) {
            postalAddress.address = {
              '@type': 'postalAddress',
              streetAddress: project.acf.json_ld_fields.street_address,
              addressLocality: project.acf.json_ld_fields.locality,
              addressRegion: project.acf.json_ld_fields.region,
              addressCountry: project.acf.json_ld_fields.country,
              postalCode: project.acf.json_ld_fields.postal_code,
            };
          }
          postalAddress.name = name;
          structuredData.ContactPoint.push(point);
          structuredData.location.push(postalAddress);
        });
        if (type === 'office') {
          this.$addSeoTag(structuredData, 'ld+json');
          resolve(structuredData);
        } else {
          homeStructuredDataObj.ContactPoint = structuredData.ContactPoint;
          homeStructuredDataObj.location = structuredData.location;
          homeStructuredDataObj.logo = data[0].acf.jellyfish_logo;
          this.$addSeoTag(homeStructuredDataObj, 'ld+json');
          resolve(homeStructuredDataObj);
        }
      } else {
        resolve(data);
      }
    });
  });
}
