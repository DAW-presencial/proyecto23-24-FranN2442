<template>
  <div class="full-page">
    <div class="employees bg-white">
      <div class="btn-actions q-ma-sm">
        <q-btn color="red-5" :label="$t('logOut')" @click="logout" />
      </div>
      <div v-for="employee in employees" v-bind:key="employee.attributes.id" class="q-ma-sm">
        <q-btn color="primary" icon="person" :label="employee.attributes.full_name"
          @click="(prompt = true), setEmployeeLogin(employee.attributes.email)" class="q-btns" />
      </div>
    </div>
    <div class="q-pa-md q-gutter-sm">
      <q-dialog v-model="prompt" persistent>
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6 text-uppercase">{{ $t('pinAccess') }}</div>
          </q-card-section>

          <q-card-section class="q-pt-none" @keydown.enter="login">
            <q-input dense v-model="employee_pin" autofocus />
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat :label="$t('reserveBtn')" v-close-popup />
            <q-btn flat :label="$t('enter')" @click="login" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </div>
</template>

<script>
import { LocalStorage } from "quasar";
import { Notify } from "quasar";
import { apiUrl } from "boot/axios";

export default {
  name: "EmploLoginPage",
  data() {
    return {
      employees: [],
      prompt: false,
      employee_email: "",
      employee_pin: "",
    };
  },
  beforeCreate() {
    let local_arr = LocalStorage.getAll();
    let rest_id = local_arr.restaurant_id;
    let token = local_arr.token_rest;

    console.log(rest_id, token);

    fetch(apiUrl + "/employees?filter[restaurant_id]=" + rest_id, {
      headers: {
        Accept: "application/vnd.api+json",
        Authorization: `Bearer ${token}`,
      },
    })
      .then((res) => res.json())
      .then((resultado) => {
        console.log(resultado.data);

        this.employees = resultado.data;
      });
  },
  methods: {
    logout() {
      LocalStorage.remove("token_rest");
      LocalStorage.remove("restaurant_id");

      Notify.create({
        message: "Cerrando sesión del restaurante",
        type: "negative",
      });

      setTimeout(() => {
        this.$router.push("restaurant-login");
      }, 3000);
    },
    setEmployeeLogin(email) {
      console.log(email);
      this.employee_email = email;
    },
    login() {
      console.log(this.employee_id, this.employee_pin);

      fetch(apiUrl + "/employee_login", {
        method: "POST",
        headers: {
          "content-Type": "application/vnd.api+json",
          Accept: "application/vnd.api+json",
        },
        body: JSON.stringify({
          email: this.employee_email,
          pin: this.employee_pin,
          device_name: "hoa",
        }),
      })
        .then((res) => res.json())
        .then((resultado) => {
          if (!resultado.data) {
            console.log(resultado);

            Notify.create({
              message: resultado.errors[0].title,
              type: "negative",
            });
          } else {
            Notify.create({
              message: "Inicio de session correcto",
              type: "positive",
            });

            LocalStorage.set("token", resultado.token.split("|")[1]);

            console.log(resultado);

            if (resultado.data.attributes.role == "admin") {
              console.log("Is admin");
              LocalStorage.set("usr", "admin");

              setTimeout(() => {
                this.$router.push("restaurant-panel");
              }, 2000);
            } else {
              LocalStorage.set("usr", "employee");
              setTimeout(() => {
                this.$router.push("restaurant-sales");
              }, 2000);
            }
          }
        });
    },
  },
};
</script>

<style scoped>
.full-page {
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #1976d2;
}

.employees {
  box-shadow: 0 20px 25px -5px rgba(58, 75, 255, 0.1),
    0 8px 10px -6px rgb(0 0 0 / 0.1);
  padding: 5px;
  border-radius: 12px;
}

.btn-actions {
  justify-content: right;
  display: flex;
}

.q-btns {
  width: 100%;
  display: flex;
  justify-content: left;
}
</style>
