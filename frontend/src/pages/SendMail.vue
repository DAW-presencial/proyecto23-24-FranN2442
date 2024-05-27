<template>
  <div class="q-pa-md q-gutter-sm bg-grey-2 flex flex-center mgTop4 my-font">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Home" icon="home" to="/" />
      <q-breadcrumbs-el :label="$t('register')" icon="person" to="/register" />
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

        <input type="submit" value="Send" class="text-uppercase">
      </form>
    </div>
  </q-page>
</template>


<script>
import emailjs from '@emailjs/browser';
import { Notify } from "quasar";

export default {
  name: 'ContactUs',
  data() {
    return {
      name: '',
      email: '',
      phone: '',
      message: '',
      errors: {
        name: null,
        email: null,
        phone: null,
        message: null,
      }
    }
  },
  methods: {
    validateForm() {
      this.errors = {
        name: null,
        email: null,
        phone: null,
        message: null,
      };

      let isValid = true;

      if (!this.name) {
        this.errors.name = 'Name is required';
        isValid = false;
      }

      if (!this.email) {
        this.errors.email = 'Email is required';
        isValid = false;
      } else if (!/\S+@\S+\.\S+/.test(this.email)) {
        this.errors.email = 'Email is invalid';
        isValid = false;
      }

      if (!this.phone) {
        this.errors.phone = 'Phone number is required';
        isValid = false;
      }

      if (!this.message) {
        this.errors.message = 'Message is required';
        isValid = false;
      }

      return isValid;
    },
    async sendEmail(e) {
      e.preventDefault();
      if (this.validateForm()) {
        try {
          const result = await emailjs.sendForm('service_bd0uxgm', 'template_wrnra98', e.target, 'DZeO-ciVfs13VEYFC');
          console.log(result.text);
          Notify.create({
            message: 'Los datos han sido enviados, correctamente!',
            type: 'positive'
          });

          this.name = '';
          this.email = '';
          this.phone = '';
          this.message = '';
        } catch (error) {
          console.error('Error al enviar el correo:', error);
          Notify.create({
            message: 'Error al enviar el correo: ' + error,
            type: 'negative'
          });
        }
      } else {
        Notify.create({
          message: 'Por favor, corrige los errores en el formulario',
          type: 'warning'
        });
      }
    },
  }
}
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

.error{
  display: block;
  color: red;
  margin-bottom: 14px

}
</style>
