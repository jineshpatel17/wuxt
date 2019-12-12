<template>
  <div :class="classModifier" >
    <div class="register__container">
      <div class="register__title">{{this.registerFormObj.form_title}}</div>
      <div class="register__box register__box--success" v-if="successResponse.length">
        {{ this.successResponse }}
      </div>
      <div class="register__box register__box--error" v-if="errorResponse.length">
        {{ this.errorResponse }}
      </div>
      <form class="register__form" v-if="registerFormDetails.length > 0"
        @change="onChangeEvent"
        @submit.prevent="onSubmit" novalidate>
          <div v-for="(ipField,index) in registerFormDetails"
            :key="index"  class="register__row" :class="widthModifier(ipField.column)">
          <div v-if="ipField.jf_field_type !== 'textarea' && ipField.jf_field_type !== 'submit' &&
            ipField.jf_field_type !== 'checkbox'">
          <input v-validate="validationType(ipField)"
            :type="ipField.jf_field_type === 'number' ? 'text' : ipField.jf_field_type"
            class="register__control" :name="ipField.jf_field_name"
            :placeholder="ipField.jf_placeholder"
            :class="errors.has(ipField.jf_field_name) ? errorClass : ''"
            v-model="fieldModels[ipField.jf_field_name]" :maxlength="ipField.max_chars"
            v-on:blur="handleBlur(errors, ipField, fieldModels)"/>
          <label :for="ipField.jf_field_name" class="register__label">
              {{ipField.jf_display_name}}
          </label>
        </div>
        <div v-if="ipField.jf_field_type === 'textarea'">
          <textarea class="register__control" :name="ipField.jf_field_name"
            :maxlength="ipField.max_chars" rows="1"
            :placeholder="ipField.jf_placeholder" v-model="fieldModels[ipField.jf_field_name]"
            @keyup="autosize($event)">
          </textarea>
          <label :for="ipField.jf_field_name" class="register__label">
            {{ipField.jf_display_name}}
            <span class="register__tip" v-if="!fieldModels[ipField.jf_field_name]">
                {{ipField.max_chars}} {{ textAreaMessage }}
            </span>
            <span class="register__tip" v-if="fieldModels[ipField.jf_field_name]">
              {{ipField.max_chars - fieldModels[ipField.jf_field_name].length}}{{textAreaMessage}}
            </span>
          </label>
        </div>
        <span class="register__hint" v-if="errors.has(ipField.jf_field_name)">
        {{ errors.first(ipField.jf_field_name) }}</span>
        <div v-if="ipField.jf_field_type === 'checkbox'"
        class="register__row--sm-12">
          <label class="register__label register__label--accept">
            <input :type="ipField.jf_field_type"
            class="register__control register__control--checkbox" :name="ipField.jf_field_name"
            v-model="fieldModels[ipField.jf_field_name]"/>
            <span class="register__checkbox"></span>
            {{ipField.jf_display_name}}
          </label>
          <span class="register__tip register__tip--accept"
            v-html="replaceRegion(ipField.jf_instructions)">
          </span>
        </div>
        <div v-if="ipField.jf_field_type === 'submit' && siteKey !== ''">
          <button :type="ipField.jf_field_type" class="register__button"
          :disabled="errors.any() || !inValid || isBtnDisabled"
          v-if="ipField.jf_field_type === 'submit' && (errors.any() || !inValid)">
          {{ ipField.jf_display_name }}
            <v-icon-base
              width="24"
              height="12"
              class="svg--chevron-right"
              icon-name="chevron-right"
              view-box="0 1 11 12">
              <icon-chevron-right />
            </v-icon-base>
          </button>
          <invisible-recaptcha class="register__button register__button--recaptcha"
            :disabled="errors.any() || !inValid || isBtnDisabled"
            v-if="ipField.jf_field_type === 'submit' && !(errors.any() || !inValid)"
            :sitekey="siteKey"
            :callback="registerData"
            type="submit" id="regitration_rechapcha">
            {{ ipField.jf_display_name }}
             <v-icon-base
              width="24"
              height="12"
              class="svg--chevron-right"
              icon-name="chevron-right"
              view-box="0 1 11 12">
              <icon-chevron-right />
            </v-icon-base>
          </invisible-recaptcha>
        </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Validator } from 'vee-validate';
import InvisibleRecaptcha from 'vue-invisible-recaptcha';


export default {
  name: 'VRegister',
  components: {
    InvisibleRecaptcha,
    VIconBase: () => import('@/shared/components/VIconBase'),
    IconChevronRight: () => import('@/shared/components/icons/IconChevronRight'),
  },
  props: {
    theme: {
      type: String,
      default: 'dark-beta',
    },
    registerDetail: {
      type: Object,
    },
  },
  data() {
    return {
      registerFormDetails: [],
      fieldModels: {},
      errorClass: 'register__inValid',
      dictionary: {},
      validationDist: {},
      textAreaMessage: '',
      registerFormObj: {},
      registerObj: {},
      booleanValue: true,
      submitDetails: {},
      successResponse: '',
      errorResponse: '',
      siteKey: '',
      isBtnDisabled: false,
      isContryAllow: true,
      contryNotAllowMsg: '',
      dataLayer: [],
    };
  },
  computed: {
    classModifier() {
      return [
        `bg--${this.theme}`,
        'register',
      ];
    },
    inValid() {
      let isvalid = true;
      Object.keys(this.dictionary.custom).forEach((key) => {
        const validation = Object.keys(this.dictionary.custom[key]);
        if (validation.includes('required')) {
          isvalid = isvalid && this.fieldModels[key];
        }
      });
      return isvalid;
    },
  },
  async created() {
    this.apiResponse();
    const data = await this.$getGeoIpDetails();
    if (data === 'RU') {
      const jfSetting = await this.$apiCallForSettings('contact_form_settings');
      this.contryNotAllowMsg =
        jfSetting.contact_form_settings.contact_form_country_validation_message;
      this.isContryAllow = false;
    }
  },
  methods: {
    widthModifier(column) {
      return [
        column === 'full' ? 'register__row--sm-12' : '',
        column === 'half' ? 'register__row--sm-6' : '',
        column === 'three' ? 'register__row--sm-4' : '',
        column === 'eight' ? 'register__row--sm-8' : '',
      ];
    },
    apiResponse() {
      this.$http
        .get(`wp/v2/event_forms/${this.registerDetail.formId}`)
        .then((response) => {
          const { acf: formDetails } = response;
          const {
            validation_messages: {
              jf_field_validation: errorMsgs,
            },
            event_form: fieldDetails,
          } = formDetails;
          this.siteKey = response.google_recaptcha_site_key;
          this.registerFormObj = formDetails;
          const registerObjArr = [];
          for (let j = 0; j < fieldDetails.length; j += 1) {
            if (fieldDetails[j].acf_fc_layout === 'one_column_row') {
              fieldDetails[j].fields.map((x) => {
                const field = x;
                field.column = 'full';
                registerObjArr.push(field);
                return field;
              });
            } else if (fieldDetails[j].acf_fc_layout === 'two_column_row') {
              fieldDetails[j].fields.map((x) => {
                const field = x;
                field.column = 'half';
                registerObjArr.push(field);
                return field;
              });
            } else if (fieldDetails[j].acf_fc_layout === 'three_column_row') {
              fieldDetails[j].fields.map((x) => {
                const field = x;
                field.column = 'three';
                registerObjArr.push(field);
                return field;
              });
            } else if (fieldDetails[j].acf_fc_layout === 'eight_column_row') {
              fieldDetails[j].fields.map((x) => {
                const field = x;
                field.column = 'eight';
                registerObjArr.push(field);
                return field;
              });
            }
          }
          this.registerFormDetails = registerObjArr;
          for (let g = 0; g < registerObjArr.length; g += 1) {
            const validType = {};
            if (!registerObjArr[g].jf_validation_type.includes('optional') && !registerObjArr[g].jf_validation_type.includes('number')) {
              for (let x = 0; x < registerObjArr[g].jf_validation_type.length; x += 1) {
                for (let w = 0; w < errorMsgs.length; w += 1) {
                  if (registerObjArr[g].jf_validation_type[x] === errorMsgs[w].jf_validation_type) {
                    const { validation_patern: message } = errorMsgs[w];
                    validType[registerObjArr[g].jf_validation_type[x]] = message.replace('##field_name##', registerObjArr[g].jf_display_name);
                  }
                }
              }
              this.validationDist[registerObjArr[g].jf_field_name] = validType;
            }
          }
          this.dictionary.custom = this.validationDist;
          Validator.localize('en', this.dictionary);
          const txtAreafield = this.registerFormDetails.find(o => o.jf_field_type === 'textarea');
          if (txtAreafield) {
            this.textAreaMessage = txtAreafield.jf_instructions.replace('##max_chars##', '');
          }
        });
    },
    registerData(recaptchaToken) {
      this.isBtnDisabled = true;
      Object.keys(this.fieldModels).forEach((key) => {
        const value = (typeof this.fieldModels[key] === 'boolean') ? Array(`${this.fieldModels[key]}`) : this.fieldModels[key];
        this.registerObj[key] = value;
      });
      if (recaptchaToken && this.isContryAllow) {
        const registerData = this.submitForm();
        this.$http.post('/wp/v2/eventlead/submission', {
          response: recaptchaToken,
          fields: registerData,
          jf_lead_type: 'event',
        }).then((response) => {
          this.successResponse = response.message;
          this.clearRegistrationForm();
        }, (errorRes) => {
          if (errorRes && errorRes.message) {
            this.errorResponse = errorRes.message;
          }
          this.clearRegistrationForm();
        });
      } else {
        this.errorResponse = this.contryNotAllowMsg;
        this.isBtnDisabled = false;
        this.clearRegistrationForm();
      }
    },
    onSubmit() {
    },
    onChangeEvent(event) {
      if (this.dataLayer.length < 0) {
        this.dataLayer = {
          event: 'formStarted',
          form: {
            id: 'Register Event',
            field: {
              name: event.target.name,
            },
          },
        };
        this.$gtm(this.dataLayer);
      }
      this.dataLayer.push(event.target.name);
    },
    handleBlur(errors, field, fieldModels) {
      const number = this.registerFormDetails ? this.registerFormDetails.length : 0;
      const fieldName = field ? field.jf_field_name : '';
      const error = errors ? errors.first(fieldName) : '';
      const value = fieldModels ? fieldModels[fieldName] : '';
      if (error) {
        const layer = {
          event: 'formError',
          attributes: {
            id: value || '',
            numberOfFields: number,
            type: 'Register Event',
            messages: {
              type: 'Error',
              id: fieldName || '',
              text: error,
            },
          },
        };
        this.$gtm(layer);
      }
    },
    validationType(formField) {
      const isRequired = !formField.jf_validation_type.includes('optional');
      // eslint-disable-next-line no-useless-escape
      const patt = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
      if (formField.jf_field_type === 'number') {
        return { required: isRequired, regex: patt };
      }
      if (isRequired) {
        if (formField.jf_validation_type.length === 1) {
          return formField.jf_validation_type[0];
        } else if (formField.jf_validation_type.includes('character_length')) {
          return formField.jf_validation_type[formField.jf_validation_type.indexOf('required')];
        }
        return formField.jf_validation_type.join('|');
      }
      return '';
    },
    autosize(event) {
      const field = event.target;
      field.style.height = 'auto';
      field.style.height = `${field.scrollHeight + 2}px`;
    },
    submitForm() {
      let optionCheckbox = false;
      Object.keys(this.registerObj).forEach((key) => {
        const value = this.registerObj[key];
        optionCheckbox = (typeof this.fieldModels[key] === 'boolean') && this.fieldModels[key] ? this.fieldModels[key] : false;
        this.submitDetails[key] = value;
      });
      let ictf = '';
      let gaMatch = '';
      if (this.getCookieByName('ictf_master', '~')) {
        ictf = this.getCookieByName('ictf_master', '~')[1];
      }
      if (this.getCookieByName('_ga', '.')) {
        gaMatch = this.getCookieByName('_ga', '.').slice(-2).join('.');
      }
      this.submitDetails.campaign_id = this.registerDetail.campaignId;
      this.submitDetails.post_id = this.registerDetail.formId;
      this.submitDetails.infinity_visitor_id = ictf;
      this.submitDetails.infinity_installation_id = 2;
      this.submitDetails.ga_client_id = gaMatch;
      this.submitDetails.ga_track_id = 'UA-3119445-23';
      this.submitDetails.full_name = this.submitDetails.first_name.concat(' ').concat(this.submitDetails.last_name);
      this.submitDetails.region = this.$isStorageSupport() ? localStorage.getItem('country') :
        this.$getRegion().split('-')[1];
      const finalReqest = {
        title: `Event Name - ${this.submitDetails.full_name}`,
        fields: this.submitDetails,
        status: 'publish',
      };
      const number = this.registerFormDetails ? this.registerFormDetails.length : 0;
      const layer = {
        event: 'formSubmission',
        attributes: {
          id: 'Submit',
          numberOfFields: number,
          type: 'Register Event',
          optionCheckbox,
        },
      };
      this.$gtm(layer);
      // INFINITY Form tracking code for events.
      // eslint-disable-next-line no-underscore-dangle
      window.parent._ictt.push(['_customTrigger', 'Form Submission', { t: 'All Events' }]);
      return finalReqest;
    },
    clearRegistrationForm() {
      this.fieldModels = {};
      this.isBtnDisabled = false;
      setTimeout(() => {
        this.successResponse = '';
        this.errorResponse = '';
      }, 5000);
    },
    getCookieByName(name, splitby) {
      if (document.cookie.match(new RegExp(`${name}=([^;]+)`))) {
        return document.cookie.match(new RegExp(`${name}=([^;]+)`))[1].split(splitby);
      }
      return false;
    },
    replaceRegion(jfInstructions) {
      if (jfInstructions && jfInstructions.includes('##locale##')) {
        return jfInstructions.replace('##locale##', this.$getRegion());
      }
      return jfInstructions;
    },
  },
  destroyed() {
    if (this.dataLayer.length > 0) {
      const formInteractionsAsString = this.dataLayer.join(' > ');
      this.dataLayer = [];
      const layer = {
        event: 'formAbandoned',
        form: {
          id: 'Register Event',
          abandonPath: formInteractionsAsString,
        },
      };
      this.$gtm(layer);
    }
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_vregister.scss';
</style>

