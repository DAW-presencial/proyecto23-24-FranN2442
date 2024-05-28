<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="q-pa-xs bg-white font-lato">
      <q-toolbar>
        <q-toolbar-title>
          <div class="layout-container">
            <a href="" class="router-link-active doc-header__logo row items-center no-wrap cursor-pointer" @click="clearLocalStorage">
              <img class="doc-header__logo-img" src="../assets/bn-logo-1.png" alt="Book-now-logo" width="48"
                height="48">
            </a>
          </div>
        </q-toolbar-title>
        <div v-if="isAuthenticated === null">
          <router-link to="/erp/restaurant-login" class="text-primary text-weight-bold cursor-pointer text-uppercase toggle">{{
          $t('restAccess') }}</router-link> &nbsp;
          <q-btn class="btn" to="/login" :label="$t('login')" color="primary" /> &nbsp;
          <q-btn class="btn" to="/register" :label="$t('register')" color="primary" /> &nbsp;
          <q-btn-toggle flat color="black" toggle-color="green" v-model="locale" @input="setLocale" :options="[{ label: 'es', value: 'es' },
        { label: 'en', value: 'en-US' }]" />
        </div>
        <div v-else>
          <q-btn class="btn" to="/profile" :label="$t('profile')" color="primary"
            v-if="this.$route.path != '/profile'" /> &nbsp;
          <q-btn class="btn" to="" :label="$t('logOut')" color="primary" @click="logOut" /> &nbsp;
          <q-btn-toggle flat color="black" toggle-color="green" v-model="locale" @input="setLocale" :options="[{ label: 'es', value: 'es' },
        { label: 'en', value: 'en-US' }]" />
        </div>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { LocalStorage } from 'quasar'
export default {

  data() {
    return {
      isAuthenticated: null,
      locale: this.$i18n.locale
    }
  },
  created() {
    this.isAuthenticated = LocalStorage.getItem("token");
  },
  methods: {
    logOut() {
      localStorage.clear();
      this.isAuthenticated = null;
      window.location.href = "";
    },
    setLocale(locale) {
      this.$i18n.locale = locale;
      import(`quasar/lang/${locale}`).then(language => {
        this.$q.lang.set(language.default);
      });
    },
    clearLocalStorage() {
      localStorage.removeItem('zip_code');
      localStorage.removeItem('zip_code_1');
      localStorage.removeItem('zip_code_2');
    }
  },
  watch: {
    locale(newLocale) {
      this.$i18n.locale = newLocale
      import(`quasar/lang/${newLocale}`).then(language => {
        this.$q.lang.set(language.default)
      })
    }
  }
}
</script>

<style scoped>
.btn {
  border-radius: 10px;
}

.q-footer {
  position: relative;
  bottom: 0;
  width: 100%;
}

.layout-container {
  display: flex;
}

.no-underline {
  text-decoration: none;
  margin: 1%;
}

@media screen and (max-width: 600px) {
  .doc-header__logo-img {
    width: 90px;
    height: 90px;
  }

  .text-uppercase {
    font-size: 10px;
  }

  .btn {
    font-size: 12px;
    padding: 4px 10px;
  }

  q-btn-toggle{
    font-size: 12px;;
  }

  .q-toolbar-title {
    display: flex;
    justify-content: center;
    width: 100%;
  }

  .layout-container {
    display: flex;
    justify-content: center;
    width: 100%;
  }

  .q-toolbar > div {
    display: flex;
    flex-direction: column;
    align-items: center;
  }


}
</style>
