<template>
  <div class="q-pa-md q-gutter-sm bg-grey-2 flex flex-center mgTop4 my-font">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Home" icon="home" to="/"  @click="handleBreadcrumbClick('home')"/>
      <q-breadcrumbs-el :label="$t('register')" icon="person" to="/register"  @click="handleBreadcrumbClick('register')"/>
    </q-breadcrumbs>
  </div>
  <q-page class="flex flex-center bg-grey-2">
    <div class="container bg-blue-2 ">
      <div class="bg-grey-2">
        <h5 class="text-left text-h5 text-bold text-uppercase mgTopN my-font">{{ $t('sendTitle') }}</h5>
      </div>
      <form @submit.prevent="sendEmail">
        <label>{{ $t('nameLabel') }}</label>
        <input type="text" v-model="name" name="user_name" :placeholder="$t('fullName')">
        <span v-if="errors.name" class="text-left error">{{ errors.name }}</span>
        <label>{{ $t('emailLabel') }}</label>
        <input type="email" v-model="email" name="user_email" :placeholder="$t('emailLabel')">
        <span v-if="errors.email" class="text-left error">{{ errors.email }}</span>
        <label>{{ $t('phoneLabel') }}</label>
        <input type="text" v-model="phone" name="user_phone" :placeholder="$t('registerPhone')">
        <span v-if="errors.phone" class="text-left error">{{ errors.phone }}</span>
        <label>{{ $t('messageLabel') }}</label>
        <textarea name="message" v-model="message" cols="30" rows="5" :placeholder="$t('messageLabel2')"></textarea>
        <span v-if="errors.message" class="text-left error">{{ errors.message }}</span>
        <input type="submit" :value="$t('register')" class="text-uppercase">
      </form>
    </div>
  </q-page>
</template>


<script>
import emailjs from '@emailjs/browser';
import { Notify } from "quasar";
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';

export default {
  name: 'ContactUs',
  setup() {
    const { t } = useI18n();
    const name = ref('');
    const email = ref('');
    const phone = ref('');
    const message = ref('');
    const errors = ref({
      name: null,
      email: null,
      phone: null,
      message: null,
    });

    const validateForm = () => {
      errors.value = {
        name: null,
        email: null,
        phone: null,
        message: null,
      };

      let isValid = true;

      if (!name.value) {
        errors.value.name = t('nameReq');
        isValid = false;
      }

      if (!email.value) {
        errors.value.email = t('emailReq');
        isValid = false;
      } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        errors.value.email = t('emailInv');
        isValid = false;
      }

      if (!phone.value) {
        errors.value.phone = t('phoneReq');
        isValid = false;
      }

      if (!message.value) {
        errors.value.message = t('messReq');
        isValid = false;
      }

      return isValid;
    };

    const sendEmail = async (e) => {
      e.preventDefault();
      if (validateForm()) {
        try {
          const result = await emailjs.sendForm('service_bd0uxgm', 'template_wrnra98', e.target, 'DZeO-ciVfs13VEYFC');
          console.log(result.text);
          Notify.create({
            message: t('dataSend'),
            type: 'positive'
          });

          name.value = '';
          email.value = '';
          phone.value = '';
          message.value = '';
        } catch (error) {
          console.error(t('error'), error);
          Notify.create({
            message: t('error') + error,
            type: 'negative'
          });
        }
      } else {
        Notify.create({
          message: t('info'),
          type: 'warning'
        });
      }
    };

    return {
      t,
      name,
      email,
      phone,
      message,
      errors,
      validateForm,
      sendEmail
    };
  },
  methods:{
    handleBreadcrumbClick(icon) {
      if (icon === 'person' || icon === 'login' || icon === 'home') {
        this.clearLocalStorage();
      }
    },
    clearLocalStorage() {
      localStorage.removeItem('zip_code');
      localStorage.removeItem('zip_code_1');
      localStorage.removeItem('zip_code_2');
    }
  }
};
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.container {
  display: block;
  margin: auto;
  text-align: center;
  border-radius: 5px;
  padding: 30px;
  width: 50%;
}

label {
  float: left;
}

input[type=text],
[type=email],
textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.mgTop4 {
  margin-top: 10px;
}

.mgTopN {
  margin-top: -8vh;
}

.error {
  display: block;
  color: red;
  margin-bottom: 14px
}
</style>
