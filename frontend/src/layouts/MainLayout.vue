<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="q-pa-xs bg-white">
      <q-toolbar>
        <q-toolbar-title>
          <div class="layout-container">
            <a href="" class="router-link-active doc-header__logo row items-center no-wrap cursor-pointer">
              <img class="doc-header__logo-img" src="../assets/bn-logo-1.png" alt="Book-now-logo" width="48"
                height="48">
            </a>
          </div>
        </q-toolbar-title>
        <div v-if="isAuthenticated === null">
          <q-btn class="btn" to="/login" label="Login" color="primary" /> &nbsp;
          <q-btn class="btn" to="/register" label="Register" color="primary" />
        </div>
        <div v-else>

          <q-btn class="btn" to="/profile" label="Profile" color="primary" v-if="this.$route.path != '/profile'"/> &nbsp;
          <q-btn class="btn" to="" label="Log out" color="primary" @click="logOut" />
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
    }
  },
  created() {
    this.isAuthenticated = LocalStorage.getItem("token")
  },
  methods: {
    logOut() {
      localStorage.clear();
      this.isAuthenticated = null;
      window.location.href = "";
    },
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
</style>
