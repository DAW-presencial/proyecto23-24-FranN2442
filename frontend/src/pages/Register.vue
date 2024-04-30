<template>
  <q-page class="flex flex-center bg-grey-2">
    <div class="q-pa-md q-gutter-sm">
      <q-dialog v-model="alert">
        <q-card>
          <q-card-section>
            <div class="text-h6">Atención</div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            Correo electrónico en uso, inténtelo de nuevo con un correo distinto.
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="OK" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
    <q-card class="q-pa-md shadow-2 my-card q-ma-md rounded-borders shadow-2" bordered>
      <q-card-section class="text-center">
        <div class="text-grey-9 text-h5 text-weight-bold">Registro</div>
      </q-card-section>
      <q-card-section>
        <q-form @submit="submitForm" class="q-gutter-md">
          <q-input v-model="formData.full_name" class="" dense outlined label="Nombre completo" /><br>
          <q-input v-model="formData.email" dense outlined label="Correo electrónico" type="email"
            :rules="emailRules" />
          <q-input v-model="formData.password" dense outlined label="Contraseña" type="password"
            :rules="passwordRules" />
          <q-input v-model="formData.tel_num" dense outlined label="Número de teléfono" type="tel"
            class="custom-input" /><br>
          <div class="text-center">
            <q-btn style="border-radius: 8px;" type="submit" color="primary" label="Registrarse" class="full-width" />
          </div>
        </q-form>
      </q-card-section>
      <q-card-section class="text-center q-pt-none">
        <div class="text-grey-8">¿Ya tiene una cuenta?
          <router-link to="/login" class="text-primary text-weight-bold cursor-pointer"
            style="text-decoration: none" >Inicie sesión</router-link>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { useQuasar } from 'quasar'
import { defineComponent, ref } from 'vue'
export default defineComponent({
  name: 'Home',
  data() {
    return {
      formData: {
        full_name: '',
        email: '',
        password: '',
        tel_num: ''
      },
      emailRules: [
        v => !!v || 'El correo electrónico es requerido',
        v => /.+@.+\..+/.test(v) || 'Correo electrónico no válido'
      ],
      passwordRules: [
        v => !!v || 'La contraseña es requerida',
        v => v.length >= 6 || 'La contraseña debe tener al menos 6 caracteres'
      ],
      errorMessage: '',
      alert: ref(false),
      $q: useQuasar(),
      timer: null
    };
  },
  computed: {
    formIsValid() {
      return (
        this.formData.full_name &&
        this.formData.email &&
        this.formData.password &&
        this.formData.tel_num
      );
    }
  },
  methods: {

    submitForm() {
      this.showLoading();
      let params = {
        full_name: this.formData.full_name,
        email: this.formData.email,
        password: this.formData.password,
        tel_num: this.formData.tel_num
      };

      fetch('http://booknow.randion.es/api/v1/users', {
        method: 'POST',
        headers: {
          'Accept': 'application/vnd.api+json',
          'Content-Type': 'application/vnd.api+json',
        },
        body: JSON.stringify({ data: { attributes: params } })
      })
        .then(res => {
          if (!res.ok && res.status === 500) {
            throw new Error('Internal Server Error');
          }
          return res.json();
        })
        .then(resultado => {
          window.location.href = "#/login";
        })
        .catch(error => {
          if (error.message === 'Internal Server Error') {
            this.alert = true;
          } else {
            this.errorMessage = alert('Se produjo un error interno del servidor. Por favor, inténtalo de nuevo más tarde.');
          }
        });

      this.formData.full_name = '';
      this.formData.email = '';
      this.formData.password = '';
      this.formData.tel_num = '';
    },

    clearLoading() {
      if (this.timer !== null) {
        clearTimeout(this.timer);
        this.$q.loading.hide();
      }
    },

    showLoading() {
      this.$q.loading.show({
        message: 'Procesando datos. Por favor, espere...',
        boxClass: 'bg-grey-2 text-grey-9',
        spinnerColor: 'primary'
      })

      this.timer = setTimeout(() => {
        this.$q.loading.hide();
        this.timer = undefined;
      }, 1000)
    }
  }
});
</script>

<style scoped>
.my-card {
  width: 25rem;
}

.bg-image {
  background-size: auto;
  background-repeat: repeat-x;
}
</style>
