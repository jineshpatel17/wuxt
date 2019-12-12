/* eslint-disable no-console */
window.addEventListener('load', () => {
  /*
  * NAVIGATION DATALAYER EVENT TRACKING
  * Some nav items have a sub nav so fetch parent of clicked item to pass top level heading.
  */
  window.dataLayer = window.dataLayer || [];
  const siteMenuButton = document.getElementsByClassName('header__right');

  // This covers all items in menu - including Nav, Contact Button, Jellyfish and Social links
  const getMenuItems = () => {
    const hamburgerMenuClick = siteMenuButton[0];
    if (hamburgerMenuClick) {
      // Need to set a timeout,
      // as the menu__open class is appended to the element after clicking the menu.
      setTimeout(() => {
        const menu = hamburgerMenuClick.children[0];
        if (menu.classList.contains('header__menu--open')) {
          // Get all the elements from the menu
          const siteMenuElements = document.getElementsByClassName('header__mega-box');

          if (siteMenuElements.length > 0) {
            const topNavigation = document.getElementsByClassName('navigation__top');
            const bottomNavigation = document.getElementsByClassName('navigation__bottom');
            if (topNavigation.length > 0) {
              const navParentElement = document.getElementsByClassName('navigation__header');
              const navChildElement = document.getElementsByClassName('navigation__link');
              // navParentElement has 2 classes as they are split into columns,
              // run a forloop to determine which column element has been clicked
              for (let i = 0; i < navParentElement.length; i += 1) {
                const getParentNavigationClick = () => {
                  const parentMenuItemClicked = this.text;
                  const parentMenuItemUrl = this.getAttribute('href');
                  window.dataLayer.push({
                    event: 'navigation',
                    category: parentMenuItemClicked,
                    id: undefined,
                    subcategory: undefined,
                    type: 'topNav',
                    url: parentMenuItemUrl,
                  });
                };
                navParentElement[i].addEventListener('click', getParentNavigationClick, false);
              }
              // All child links are split into 2 columns under their parent column
              for (let i = 0; i < navChildElement.length; i += 1) {
                const getChildtNavigationClick = () => {
                  // e.preventDefault();
                  const childMenuItemClicked = this.text;
                  const childMenuItemUrl = this.getAttribute('href');
                  const childMenuItemsParent =
                    this.parentElement.parentElement.previousSibling.previousSibling.text;
                  window.dataLayer.push({
                    event: 'navigation',
                    category: childMenuItemsParent,
                    id: undefined,
                    subcategory: childMenuItemClicked,
                    type: 'topNav',
                    url: childMenuItemUrl,
                  });
                };
                navChildElement[i].addEventListener('click', getChildtNavigationClick, false);
              }
            }

            if (bottomNavigation.length > 0) {
              const navContactButton = document.getElementsByClassName('navigation__contact');
              const navSocialShareElements = document.getElementsByClassName('navigation__social-link');
              const navJellyfishSites = document.getElementsByClassName('navigation__links');
              // Pass Contact Button Click on Menu to DataLayer
              const getNavigationContactButtonClick = () => {
                const contactButtonText = this.children[0].firstChild.innerText;
                window.dataLayer.push({
                  event: 'navigation',
                  category: 'Button',
                  id: undefined,
                  subcategory: contactButtonText,
                  type: 'bottomNav',
                  url: undefined,
                });
              };
              navContactButton[0].addEventListener('click', getNavigationContactButtonClick, false);

              // Pass Social Share events into DataLayer
              for (let i = 0; i < navSocialShareElements.length; i += 1) {
                const getSocialShareClick = () => {
                  const socialShareLinkText = this.getAttribute('aria-label');
                  const socialShareLinkUrl = this.getAttribute('href');
                  window.dataLayer.push({
                    event: 'navigation',
                    category: 'Social Share',
                    id: undefined,
                    subcategory: socialShareLinkText,
                    type: 'bottomNav',
                    url: socialShareLinkUrl,
                  });
                };
                navSocialShareElements[i].addEventListener('click', getSocialShareClick, false);
              }
              // Pass Other Jellyfish website (Dynamix, Training) clicks to DataLayer
              for (let i = 0; i < navJellyfishSites.length; i += 1) {
                const getOtherJellyfishSiteClick = () => {
                  if (this.children && this.children.length) {
                    const jellyfishSiteName = this.children[0].getAttribute('aria-label');
                    const jellyfishSiteUrl = this.children[0].getAttribute('href');
                    window.dataLayer.push({
                      event: 'navigation',
                      category: 'Jellyfish Sites',
                      id: undefined,
                      subcategory: jellyfishSiteName,
                      type: 'bottomNav',
                      url: jellyfishSiteUrl,
                    });
                  }
                };
                navJellyfishSites[i].addEventListener('click', getOtherJellyfishSiteClick, false);
              }
            }
          }
        }
      }, 100);
    }
  };
  // Nav menu items only appear after in the HTML after a click
  siteMenuButton[0].addEventListener('click', getMenuItems, false);

  /*
  * PAGE DATALAYER EVENT TRACKING
  */
  let pageUrl = window.location.pathname;
  const regex = /[A-z]+-[A-z]+/g;
  const pageLocale = pageUrl.match(regex)[0];
  // need to check for slashes at the end of url
  const lastCharacterOfUrl = pageUrl.slice(-1);

  // if last character of URL is a slash, remove it.
  if (lastCharacterOfUrl === '/') {
    pageUrl = pageUrl.slice(0, -1);
  }
  const splitPageUrl = pageUrl.split('/');
  const pageCountryAndLanguage = pageLocale.split('-');

  const pageLanguage = pageCountryAndLanguage[0];
  const pageCountry = pageCountryAndLanguage[1];
  let hostname = window.location.hostname;
  if (hostname.includes('local')) {
    hostname = 'DEVELOPMENT';
  } else if (hostname.includes('stage')) {
    hostname = 'STAGE';
  } else if (hostname.includes('uat')) {
    hostname = 'UAT';
  } else {
    hostname = 'PRODUCTION';
  }

  // Observe the change of avtive class on side bar nav links
  // Pass each mutation as DataLayer event
  const sideBarNavigationObserver = (pageUrlSlug) => {
    setTimeout(() => {
      // Select the node that will be observed for mutations
      const targetNode = document.getElementsByClassName('progress__item--active');
      console.log(targetNode[0]);
      // Options for the observer (which mutations to observe)
      const config = { attributes: true, classList: true, childList: true, subtree: true, attributeFilter: ['class'] };

      // Callback function to execute when mutations are observed
      const callback = (mutationsList) => {
        mutationsList.forEach((mutation) => {

          if (mutation.type === 'attributes') {
            let activeSideNavLink = targetNode[0].children[0].innerText;

            activeSideNavLink = `/${pageUrlSlug}/${activeSideNavLink.toLowerCase()}`;
            console.log(activeSideNavLink);
            window.dataLayer.push({
              event: 'virtualPageview',
              vPageName: activeSideNavLink,
            });
          } else {
            console.log('The ', mutation.attributeName, ' attribute was modified.');
          }
        });
      };
      // Create an observer instance linked to the callback function
      const observer = new MutationObserver(callback);
      // Start observing the target node for configured mutations
      observer.observe(targetNode[0], config);
      // Later, you can stop observing
      // observer.disconnect();
    }, 3000);
  };

  // check for homepage
  if (splitPageUrl.length < 3) {
    window.dataLayer.push({
      event: 'allPages',
      page: {
        author: undefined,
        category1: 'homepage',
        category2: undefined,
        contentType: undefined,
        contentWordCount: undefined,
        environment: hostname,
        language: pageLanguage,
        region: pageCountry,
      },
    });
  } else if (splitPageUrl.length < 4) {
    const pageUrlSlug = splitPageUrl[2];
    // Post type the page is associated with.
    let pagePostType;
    // Parent page of the current page.
    let parentPage;
    console.log(pageUrlSlug);
    setTimeout(() => {
      // Pages with parent page
      if (pageUrlSlug === 'working-at-jellyfish' || pageUrlSlug === 'people' || pageUrlSlug === 'offices' || pageUrlSlug === 'news-insights' || pageUrlSlug === 'work' || pageUrlSlug === 'jobs') {
        parentPage = document.getElementsByClassName('bg-highlight__text')[0].outerText;
        parentPage = parentPage.substr(2);

        if (pageUrlSlug === 'jobs') {
          pagePostType = document.getElementsByClassName('vfilter__meta')[0].outerText;
        } else {
          pagePostType = document.getElementsByClassName('section-title__category')[0].outerText;
        }

      } else if (pageUrlSlug === 'we-do' || pageUrlSlug === 'we-are' || pageUrlSlug === 'contact-us') { // Top level pages

        if (pageUrlSlug === 'contact-us') {
          pagePostType = document.getElementsByClassName('contact-title__meta')[0].outerText;
          console.log('hello', pagePostType);
        } else {
          pagePostType = document.getElementsByClassName('section-title__category')[0].outerText;
        }
        parentPage = undefined;
      } else if (pageUrlSlug === 'expertise') {
        parentPage = undefined;
        pagePostType = undefined;
      }

      // console.log('test', document.getElementsByClassName('contact-title__meta').outerText);
      window.dataLayer.push({
        event: 'allPages',
        page: {
          author: undefined,
          category1: parentPage,
          category2: pageUrlSlug,
          contentType: pagePostType,
          contentWordCount: undefined,
          environment: hostname,
          language: pageLanguage,
          region: pageCountry,
        },
      });
    }, 5000);
    // Pages that have sidebar nagvigation
    if (pageUrlSlug === 'we-do' || pageUrlSlug === 'we-are' || pageUrlSlug === 'working-at-jellyfish' || pageUrlSlug === 'expertise') {

      // Product categories page renders content in layers.
      sideBarNavigationObserver(pageUrlSlug);
      // Active class is only available after first scroll,
      // as it sits behind the hero carousel (layer 2)
      // let scrollOnce = true;
      // const observeScroll = () => {
      //   while (scrollOnce) {
      //     console.log('one scroll');
      //     sideBarNavigationObserver(pageUrlSlug);
      //     scrollOnce = false;
      //   }
      // };
      // For for first scroll.
      // window.addEventListener('scroll', observeScroll);
    } else if (pageUrlSlug === 'contact-us' || pageUrlSlug === 'events') {
      setTimeout(() => {

        let countInteractions = 0;
        const formInteractions = [];
        let formInteractionsAsString;
        let formType;
        let formFields;
        let formFieldCount;
        let formJfCommsOptinCheckbox;
        // let formFieldErrorMessage;
        let formSubject;
        let jfCommsOptin = false;
        let formSubmit = false;
        let formSubmitElement;
        let formSubmitElementID;

        const getFormFieldSubjectFieldChange = (clickedElement) => {
          const elementClicked = clickedElement.text;
          return elementClicked;
        };
        // Monitor Form Start and Abandons
        const fieldInteractions = (interaction, fieldInteracted) => {
          if (interaction) {
            countInteractions += 1;
            formInteractions.push(fieldInteracted);
            // Send and event for first interaction
            if (countInteractions === 1) {
              console.log('first interaction trigger', fieldInteracted);
              window.dataLayer.push({
                event: 'formStarted',
                form: {
                  id: formType,
                  field: {
                    name: fieldInteracted,
                  },
                },
              });
            } else {
              formInteractionsAsString = formInteractions.join(' > ');
            }
          }
        };

        // Send for submission as dataLayer event.
        const formSubmitClick = () => {
          console.log(formSubject, formFieldCount, formType, jfCommsOptin, formSubmit);
          if (formSubmit) {
            const formSubmitted = () => {
              // e.preventDefault;
              console.log('prepare submission');
              window.dataLayer.push({
                event: 'formSubmission',
                attributes: {
                  id: formSubject,
                  numberOfFields: formFieldCount,
                  type: formType,
                  optinCheckbox: jfCommsOptin,
                },
              });
            };
            formSubmitElement[0].addEventListener('click', formSubmitted, false);
          }
        };

        if (pageUrlSlug === 'contact-us') {
          formType = 'Contact Us';
          // Loop through all form fields, looking for any changes
          formFields = document.getElementsByClassName('contact-form__control');
          formFieldCount = formFields.length + 1;
          formJfCommsOptinCheckbox = document.getElementsByClassName('contact-form__control--checkbox');
          // Get FormSubject
          const formSubjectFields = document.getElementsByClassName('contact-tabs__tab');
          if (formSubjectFields && formSubjectFields.length) {
            // First element in list will always be the active value.
            formSubject = formSubjectFields[0].text;
          }
          // Form submit
          formSubmitElement = document.getElementsByClassName('contact-form__button');

          for (let i = 0; i < formSubjectFields.length; i += 1) {
            // Fetch the current active value, trigger on click if this changes
            // eslint-disable-next-line no-loop-func
            formSubjectFields[i].addEventListener('click', () => {
              formSubject = getFormFieldSubjectFieldChange(formSubjectFields[i]);
              fieldInteractions(true, formSubject);
            });
          }
        } else if (pageUrlSlug === 'events') {
          // formType = 'Events';
          // formSubject = 'All Events';
          // formFields = document.getElementsByClassName('register__control ');
          // formJfCommsOptinCheckbox = document.getElementsByClassName('register__control--checkbox');
          // formFieldCount = formFields.length;
          // formSubmitElement = document.getElementsByClassName('register__button');
        }

        // If user moves away from form, trigger abandon event
        window.addEventListener('beforeunload', () => {
          console.log('mroe than one interaction detected', formInteractionsAsString);
          window.dataLayer.push({
            event: 'formAbandoned',
            form: {
              id: formType,
              abandonPath: formInteractionsAsString,
            },
          });
        });

        // Check for form field changes, return if sumission is ready to be posted
        const getFormFieldChange = (formFieldChange) => {
          console.log('form field change');
          const formField = formFieldChange.getAttribute('name');
          fieldInteractions(true, formField);
          // Only check for field errors when user has moved away from field
          const formFieldError = formFieldChange.getAttribute('aria-invalid');
          // Get form field error message, if any and pass all occurence to DataLayer
          formSubmit = false;
          formSubmitElementID = formSubmitElement[0].getAttribute('id');
          if (formFieldError === 'true') {
            const formFieldErrorType = 'Error';
            // If an error is valid the span created is a sibling of form fields parent
            const formFieldErrorMessage =
              formFieldChange.parentElement.nextElementSibling.outerText;
            // if (formFieldChange.oninvalid) {
            console.log('form element marked as error', formField);
            window.dataLayer.push({
              event: 'formError',
              attributes: {
                id: formSubject,
                numberOfFields: formFieldCount,
                type: formType,
                messages: {
                  type: formFieldErrorType,
                  id: formField,
                  text: formFieldErrorMessage,
                },
              },
            });
          } else if (formSubmitElementID) {
            formSubmit = true;
          }
          return formSubmit;
        };

        // Monitor change of this checkbox updating variable everytime it does change.
        formJfCommsOptinCheckbox[0].addEventListener('change', () => {
          jfCommsOptin = formJfCommsOptinCheckbox[0].checked;
          fieldInteractions(true, 'sign_up_for_jellyfish_communication');
        });

        for (let i = 0; i < formFields.length; i += 1) {
          console.log(formFields[i], formSubmit);
          if (formFields[i].getAttribute('name') !== 'sign_up_for_jellyfish_communication') {
            // eslint-disable-next-line no-loop-func
            formFields[i].addEventListener('blur', () => {
              formSubmit = getFormFieldChange(formFields[i], i);
              // If form submit ready then send trigger submission function.
              if (formSubmit) {
                formSubmitClick();
              }
            });
          }
        }
        const contactFooterLinks = document.getElementsByClassName('contact-footer__link');
        // Get email link click event
        const contactEmailLinkClick = () => {
          // e.preventDefault;
          const email = this.text;
          console.log('email link', email);
        };
        contactFooterLinks[0].addEventListener('click', contactEmailLinkClick, false);

        // Get email link click event
        // const contactFooterLinks = document.getElementsByClassName('contact-footer__link');
        // const contactEmailLinkClick = (e) => {
        //   e.preventDefault;
        //   const email = this.text;
        //   console.log('email link', email);
        // };
        // contactFooterLinks[0].click=contactEmailLinkClick;

        // Get office page link click event
        const officeLinkClick = () => {
          // e.preventDefault;
          const office = this.text;
          console.log('office link', office);
        };
        contactFooterLinks[1].addEventListener('click', officeLinkClick, false);
      }, 15000);
    } else if (pageUrlSlug === 'jobs') {
      // Job filters
      const jobFilterOptions = document.getElementsByClassName('vfilter__field');
      for (let i = 0; i < jobFilterOptions.length; i += 1) {
        const getFilterSelection = () => {
          // e.preventDefault;
          const filterType = this.previousElementSibling.innerText;
          const filterSelection = this.value;
          window.dataLayer.push({
            event: 'filter',
            attributes: {
              name: filterType,
              value: filterType,
            },
          });
          console.log('email link', filterSelection, filterType);
        };
        jobFilterOptions[i].addEventListener('change', getFilterSelection, false);
      }
    }
  } else if (splitPageUrl.length < 5) {
    const pageUrlSlug = splitPageUrl[3];

    // console.log('pages', pageUrlSlug);
    // Check for all child pages of news/insights.
    if (splitPageUrl[2] === 'news-insights') {
      console.log('news and insights');
      setTimeout(() => {
        const articleType = document.getElementsByClassName('section-title__category')[0].outerText;
        const articleAuthor = document.getElementsByClassName('section-title__author-name')[0].outerText;
        const articleTitleText = document.getElementsByClassName('section-title__title')[0].outerText;
        const articleContent = document.getElementsByClassName('article__text')[0].outerText;

        const articleWordCount = articleContent.split(' ').length;
        console.log('test word count', articleWordCount);
        window.dataLayer.push({
          event: 'allPages',
          page: {
            author: articleAuthor,
            category1: splitPageUrl[2],
            category2: pageUrlSlug,
            contentType: articleType,
            contentWordCount: articleWordCount,
            environment: hostname,
            language: pageLanguage,
            region: pageCountry,
            type: 'content',
            articleTitle: articleTitleText,
          },
        });
      }, 10000);
    } else if (splitPageUrl[2] === 'case-study') {
      console.log('case study');
      setTimeout(() => {
        const caseStudyTitle = document.getElementsByClassName('tag-line__title')[0].outerText;
        window.dataLayer.push({
          event: 'allPages',
          page: {
            author: undefined,
            category1: splitPageUrl[2],
            category2: pageUrlSlug,
            contentType: 'case study',
            contentWordCount: undefined,
            environment: hostname,
            language: pageLanguage,
            region: pageCountry,
            type: 'content',
            articleTitle: caseStudyTitle,
          },
        });
      }, 20000);
    }
  }
});
