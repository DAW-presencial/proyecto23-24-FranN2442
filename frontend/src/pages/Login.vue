<template>
  <div class="q-pa-md q-gutter-sm bg-grey-2 flex flex-center mgTop4 font-lato">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Home" icon="home" to="/" @click="handleBreadcrumbClick('home')" />
    <q-breadcrumbs-el :label="$t('register')" icon="person" to="/register" @click="handleBreadcrumbClick('register')" />
    <q-breadcrumbs-el :label="$t('login')" icon="login" to="/profile" @click="handleBreadcrumbClick('login')" />
    </q-breadcrumbs>
  </div>
  <q-page class="flex flex-center bg-grey-2 font-lato">
    <div class="q-pa-md q-gutter-sm">
      <q-dialog v-model="alert">
        <q-card>
          <q-card-section>
            <div class="text-h6">{{ $t('info') }}</div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            {{ $t('infoLog') }}
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="OK" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
    <q-card class="q-pa-md shadow-2 my_card restMg" bordered @keydown.enter="login">
      <q-card-section class="text-center">
        <div class="text-grey-9 text-h5 text-weight-bold">Login</div>
      </q-card-section>
      <q-card-section class="q-gutter-md">
        <q-input dense outlined v-model="email" :label="$t('email')"
          :rules="[val => !!val || 'Email is required', val => emailRegex.test(val) || 'Correo electrónico no válido']" />
        <q-input dense outlined v-model="password" type="password" :label="$t('pass')"
          :rules="[val => !!val || 'Password is required', val => val.length >= 1 || 'La contraseña debe tener al menos 8 caracteres']" />
      </q-card-section>
      <q-card-section>
        <q-btn style="border-radius: 8px;" color="primary" rounded size="md" :label="$t('enter')" class="full-width"
          @click="login" />
      </q-card-section>
      <q-card-section class="text-center q-pt-none">
        <div class="text-grey-8">{{ $t('createAcc') }}
          <router-link to="/register" class="text-primary text-weight-bold cursor-pointer"
            style="text-decoration: none">{{ $t('register') }}</router-link>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { LocalStorage, useQuasar, QSpinnerGears } from 'quasar'
import { useI18n } from 'vue-i18n';
import { apiUrl } from 'boot/axios'

export default defineComponent({
  name: 'Home',
  setup() {
    const { t } = useI18n();
    const alert = ref(false)
    const email = ref('')
    const password = ref('')
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    const $q = useQuasar()

    const submitForm = () => {
      if (!email.value || !password.value) {
        console.log('Please fill in all fields.')
        return
      }
      console.log('Email:', email.value)
      console.log('Password:', password.value)
    }

    const getDeviceName = () => {
      let navInfo = window.navigator.appVersion.toLowerCase();
      let so = 'Sistema Operativo';

      if (navInfo.indexOf('win') !== -1) {
        so = 'Windows';
      } else if (navInfo.indexOf('linux') !== -1) {
        so = 'Linux';
      } else if (navInfo.indexOf('mac') !== -1) {
        so = 'Macintosh';
      }

      return so;
    }

    function showCustom() {
      let okConfirmed = false;

      const dialog = $q.dialog({
        title: t('loadingProfile'),
        dark: true,
        message: '0%',
        progress: {
          spinner: QSpinnerGears,
          color: 'amber'
        },
        persistent: true,
        ok: false
      })

      let percentage = 0
      const interval = setInterval(() => {
        percentage = Math.min(100, percentage + Math.floor(Math.random() * 22))

        dialog.update({
          message: `${percentage}%`
        })

        if (percentage === 100) {
          clearInterval(interval)

          dialog.update({
            title: t('welcome'),
            message: t('messageLog'),
            progress: false,
            ok: true
          });

          dialog.onOk(() => {
            okConfirmed = true;
            window.location.href = "";
          });
        }
      }, 200);
    }
    return {
      email,
      password,
      submitForm,
      emailRegex,
      alert,
      getDeviceName,
      showCustom,
    }
  },

  methods: {
    login() {
      let params = {
        "email": this.email,
        "password": this.password,
        "device_name": this.getDeviceName()
      }
      console.log(params);
      fetch(apiUrl + '/user_login', {
        method: "POST",
        headers: {
          'Accept': 'application/vnd.api+json',
          'Content-Type': 'application/vnd.api+json'
        },
        body: JSON.stringify(params)
      })
        .then(res => {
          if (!res.ok && res.status === 422) {
            throw new Error('Unprocessable Content');
          }
          return res.json();
        })
        .then(resultado => {
          LocalStorage.set("token", resultado.token);
          LocalStorage.set("data", resultado.data.attributes);
          LocalStorage.set("usrid", resultado.data.id);
          console.log(localStorage.getItem("token"));
          localStorage.removeItem('zip_code');
          localStorage.removeItem('zip_code_1');
          localStorage.removeItem('zip_code_2');
          this.showCustom()
        })
        .catch(error => {
          if (error.message === 'Unprocessable Content') {
            this.alert = true;
          } else {
            console.log(error);
            this.errorMessage = alert('Se produjo un error interno del servidor. Por favor, inténtalo de nuevo más tarde.');
          }
        });
    },
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
});
</script>

<style scoped>
.my_card {
  width: 25rem;
  border-radius: 8px;
  box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

.mgTop4 {
  margin-top: 10px;
}

.restMg {
  margin-top: -30vh;
}
</style>
