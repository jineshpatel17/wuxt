<template>
  <div class="contact-form" >
    <div class="contact-form__container">
      <div class="contact-form__title">{{this.registerFormObj.form_title}}</div>
      <div class="contact-form__box contact-form__box--success" v-if="successResponse.length">
        {{ this.successResponse }}
      </div>
      <div class="contact-form__box contact-form__box--error" v-if="errorResponse.length">
        {{ this.errorResponse }}
      </div>
      <form class="contact-form__form" v-if="registerFormDetails.length > 0"
        @click="onFormClick"
        @change="onChangeEvent"
        @submit.prevent="onSubmit" novalidate>
        <div v-for="(ipField,index) in registerFormDetails"
          :key="index" class="contact-form__row" :class="widthModifier(ipField.column)">
        <div
          v-if="isTypeExist(ipField.jf_field_type) && isFieldExist(ipField)">
          <input v-validate="validationType(ipField)"
            :type="ipField.jf_field_type === 'number' ? 'text' : ipField.jf_field_type"
            class="contact-form__control" :name="ipField.jf_field_name"
            :placeholder="ipField.jf_placeholder"
            :class="errors.has(ipField.jf_field_name) ? errorClass : ''"
            v-model="fieldModels[ipField.jf_field_name]" :maxlength="ipField.max_chars"
            v-on:blur="handleBlur(errors, ipField, fieldModels)"/>
          <label :for="ipField.jf_field_name" class="contact-form__label">
              {{ipField.jf_display_name}}
          </label>
        </div>
        <div class="contact-form__textarea"
          v-if="ipField.jf_field_type === 'textarea' && isFieldExist(ipField)">
          <label class="contact-form__label--repeater">
            {{ipField.jf_placeholder}}
          </label>
          <textarea class="contact-form__control contact-form__control--textarea"
            :name="ipField.jf_field_name"
            :maxlength="ipField.max_chars"
            v-model="fieldModels[ipField.jf_field_name]">
          </textarea>
          <!-- <label :for="ipField.jf_field_name" class="contact-form__label">
            {{ipField.jf_display_name}}
            <span class="contact-form__tip" v-if="!fieldModels[ipField.jf_field_name]">
                {{ipField.max_chars}} {{ textAreaMessage }}
            </span>
            <span class="contact-form__tip" v-if="fieldModels[ipField.jf_field_name]">
              {{ipField.max_chars - fieldModels[ipField.jf_field_name].length}}{{textAreaMessage}}
            </span>
          </label> -->
        </div>
        <div v-if="ipField.jf_field_type === 'repeater'
        && isFieldExist(ipField)">
          <label class="contact-form__label--repeater">
            {{ipField.jf_display_name}}
          </label>
          <div class="contact-form__repeater">
            <div class="contact-form__repeater-labels"
              v-for="(item, r) in ipField.repeater" :key="r">
              <label
                class="contact-form__label contact-form__repeater-label
                  contact-form__label--accept">
                <input
                  class="contact-form__control contact-form__control--checkbox"
                  :type="ipField.repeater_field"
                  :name="ipField.jf_field_name"
                  v-model="fieldModels[item.repeater_field_value]"
                  @change="repeaterChangeEvent($event, ipField, item.repeater_field_value)" />
                  <span class="contact-form__checkbox contact-form__repeater-checkbox"></span>
                {{item.repeater_field_value}}
              </label>
            </div>
          </div>
        </div>
        <span class="contact-form__hint" v-if="errors.has(ipField.jf_field_name)">
          {{ errors.first(ipField.jf_field_name) }}</span>
        <div v-if="ipField.jf_field_type === 'checkbox' && isFieldExist(ipField)"
          class="contact-form__row--sm-12">
          <label class="contact-form__label contact-form__label--accept">
            <input :type="ipField.jf_field_type"
            class="contact-form__control contact-form__control--checkbox"
            :name="ipField.jf_field_name"
            v-model="fieldModels[ipField.jf_field_name]"/>
            <span class="contact-form__checkbox"></span>
            {{ipField.jf_display_name}}
            <span class="contact-form__tip"
              v-if="ipField.jf_instructions && ipField.jf_instructions.length"
              v-html="replaceRegion(ipField.jf_instructions)">
            </span>
          </label>
        </div>
        <div v-if="ipField.jf_field_type === 'submit' && siteKey !== ''">
          <button :type="ipField.jf_field_type" class="contact-form__button"
          :disabled="errors.any() || !inValid || isBtnDisabled"
          v-if="ipField.jf_field_type === 'submit' && (errors.any() || !inValid) && !isLoading">
              {{ ipField.jf_display_name }}
              <v-icon-base
                width="6"
                height="12"
                class="svg--chevron-right"
                icon-name="chevron-right"
                view-box="0 0 6 12">
                <icon-chevron-right />
              </v-icon-base>
          </button>
          <invisible-recaptcha class="contact-form__button"
            :disabled="errors.any() || !inValid || isBtnDisabled"
            v-if="ipField.jf_field_type === 'submit' && !(errors.any() || !inValid) && !isLoading"
            :sitekey="siteKey"
            :callback="registerData"
            type="submit" id="regitration_rechapcha">
              {{ ipField.jf_display_name }}
              <v-icon-base
                width="6"
                height="12"
                class="svg--chevron-right"
                icon-name="chevron-right"
                view-box="0 0 6 12">
                <icon-chevron-right />
              </v-icon-base>
          </invisible-recaptcha>
          <span v-if="isLoading" class="contact-form__load-icon"></span>
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
  name: 'ContactForm',
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
    id: {
      type: Number,
    },
    subject: {
      type: String,
    },
    selectedTab: {
      type: String,
    },
  },
  watch: {
    subject(value) {
      if (!this.formStart) {
        this.formStart = {
          event: 'formStarted',
          form: {
            id: this.subject,
            field: {
              name: value,
            },
          },
        };
        this.$gtm(this.formStart);
      }
      this.dataLayer.push(value);
    },
    selectedTab(val) {
      this.selectedTab = val;
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
      isLoading: false,
      dataLayer: [],
      formStart: null,
    };
  },
  computed: {
    inValid() {
      let isvalid = true;
      Object.keys(this.dictionary.custom).forEach((key) => {
        const validation = Object.keys(this.dictionary.custom[key]);
        if (validation.includes('required')) {
          const fieldTab = this.dictionary.custom[key].tab;
          if (fieldTab && (fieldTab === 'all' || fieldTab === this.selectedTab)) {
            isvalid = isvalid && this.fieldModels[key];
          }
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
        column === 'full' ? 'contact-form__row--sm-12' : '',
        column === 'half' ? 'contact-form__row--sm-6' : '',
        column === 'three' ? 'contact-form__row--sm-4' : '',
        column === 'eight' ? 'contact-form__row--sm-8' : '',
      ];
    },
    isTypeExist(type) {
      const listType = ['textarea', 'submit', 'checkbox', 'repeater'];
      if (!listType.includes(type)) {
        return type;
      }
      return false;
    },
    isFieldExist(ipField) {
      return (ipField.show_in_tab === 'all' || ipField.show_in_tab === this.selectedTab);
    },
    apiResponse() {
      this.$http
        .get(`wp/v2/jf_contact_form/${this.id}`)
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
              validType.tab = registerObjArr[g].show_in_tab;
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
      this.isLoading = true;
      if (recaptchaToken && this.isContryAllow) {
        const leadData = this.submitForm();
        this.$http.post('/wp/v2/jflead/submission', {
          response: recaptchaToken,
          fields: leadData,
          jf_lead_type: 'contact',
        }).then((response) => {
          if (response.code && response.code === 'error') {
            if (response && response.message) {
              this.errorResponse = response.message;
            }
            this.clearRegistrationForm();
            this.isLoading = false;
          } else {
            this.successResponse = response.message;
            this.clearRegistrationForm();
            this.isLoading = false;
            this.$eventBus.$emit('REDIRECT__SUCCESS', true);
          }
        }, (errorRes) => {
          if (errorRes && errorRes.message) {
            this.errorResponse = errorRes.message;
          }
          this.clearRegistrationForm();
          this.isLoading = false;
        });
      } else {
        this.errorResponse = this.contryNotAllowMsg;
        this.isBtnDisabled = false;
        this.isLoading = false;
        this.clearRegistrationForm();
      }
    },
    onSubmit() {
    },
    onChangeEvent(event) {
      this.dataLayer.push(event.target.name);
    },
    onFormClick(event) {
      if (!this.formStart) {
        this.formStart = {
          event: 'formStarted',
          form: {
            id: this.subject,
            field: {
              name: event.target.name,
            },
          },
        };
        this.$gtm(this.formStart);
      }
    },
    repeaterChangeEvent(event, fieldDetail, value) {
      let clone = this.fieldModels[fieldDetail.jf_field_name];
      if (event.target.checked) {
        if (clone) {
          clone += `,${value}`;
        } else {
          // eslint-disable-next-line no-param-reassign
          clone = `${value}`;
        }
      } else if (clone && clone.length) {
        const nameList = clone.split(',');
        nameList.splice(nameList.indexOf(value), 1);
        clone = nameList.toString();
      }
      this.fieldModels[fieldDetail.jf_field_name] = clone;
      this.fieldModels = Object.assign({}, this.fieldModels);
    },
    repeaterVal(list) {
      if (list && list.length) {
        return list.length;
      }
      return null;
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
            type: this.subject,
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
      Object.keys(this.fieldModels).forEach((key) => {
        const value = (typeof this.fieldModels[key] === 'boolean') ? Array(`${this.fieldModels[key]}`) : this.fieldModels[key];
        optionCheckbox = (typeof this.fieldModels[key] === 'boolean') && this.fieldModels[key] ? this.fieldModels[key] : false;
        const field = this.registerFormDetails.find(o => o.jf_field_name === key);
        if (field && field.show_in_tab &&
          (field.show_in_tab === 'all' || field.show_in_tab === this.selectedTab)) {
          this.submitDetails[key] = value;
        }
      });
      let ictf = '';
      let gaMatch = '';
      if (this.getCookieByName('ictf_master', '~')) {
        ictf = this.getCookieByName('ictf_master', '~')[1];
      }
      if (this.getCookieByName('_ga', '.')) {
        gaMatch = this.getCookieByName('_ga', '.').slice(-2).join('.');
      }
      this.submitDetails.post_id = this.id;
      this.submitDetails.subject = this.subject;
      this.submitDetails.infinity_visitor_id = ictf;
      this.submitDetails.infinity_installation_id = 2;
      this.submitDetails.ga_client_id = gaMatch;
      this.submitDetails.ga_track_id = 'UA-3119445-23';
      this.submitDetails.region = this.$isStorageSupport() ? localStorage.getItem('country') :
        this.$getRegion().split('-')[1];
      const finalRequest = {
        title: `${this.subject} - ${this.submitDetails.full_name}`,
        fields: this.submitDetails,
        status: 'publish',
      };
      const number = this.registerFormDetails ? this.registerFormDetails.length : 0;
      const layer = {
        event: 'formSubmission',
        attributes: {
          subject: this.subject,
          numberOfFields: number,
          optionCheckbox,
        },
      };
      this.$gtm(layer);
      // INFINITY Form tracking code.
      // eslint-disable-next-line no-underscore-dangle
      window.parent._ictt.push(['_customTrigger', 'Form Submission', { t: this.subject }]);
      return finalRequest;
    },
    clearRegistrationForm() {
      this.fieldModels = {};
      this.submitDetails = {};
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
          id: this.subject,
          abandonPath: formInteractionsAsString,
        },
      };
      this.$gtm(layer);
    }
  },
};
</script>

<style lang="scss" scoped>
@import './src/assets/scss/components/_contactform.scss';
</style>
