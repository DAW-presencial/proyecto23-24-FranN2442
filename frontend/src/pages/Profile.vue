<template>
  <q-page class="bg-grey-2">
    <div class="q-pa-md shadow-2 my_card bg-white">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 300px">
          <div class="text-h5 text-weight-bold">Datos personales</div>
          <div>
            <q-toggle v-model="readonly" label="Readonly" />
            <q-toggle v-model="disable" label="Disable" />
          </div>



          <q-input v-model="this.data.attributes.full_name" label="Name" placeholder="Nuevo nombre" outlined :readonly="readonly" :disable="disable" />
          <q-input v-model="this.data.attributes.tel_num" label="Phone" placeholder="Nuevo teléfono" outlined :readonly="readonly" :disable="disable" />
          <q-input v-model="this.data.attributes.email" label="Email" placeholder="Nuevo email" outlined :readonly="readonly" :disable="disable" />
          <q-input v-model="this.data.attributes.password" label="Password" placeholder="Nuevo password" outlined :readonly="readonly" :disable="disable" />


          <div class="text-right q-gutter-md">
            <q-btn class="btn" label="Editar" color="green" />
          </div>
        </div>
      </q-card-section>
    </div>
    <div class="q-pa-md shadow-2 my_card bg-white">
      <q-card-section>
        <div class="text-h6">Reservas</div>
      </q-card-section>
      <q-card-section class="text-center flex" v-if="userReservations != []">
        <div class="q-pa-md q-ma-md shadow-2" style="max-width: 300px" v-for="reservation in this.userReservations"
          :key="reservation.id">
          <q-field outlined label="Código Reserva" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.reservation_code }}
              </div>
            </template>
          </q-field>
          <q-field outlined label="Fecha" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.date }}
              </div>
            </template>
          </q-field>
          <q-field outlined label="Hora" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.hour }}
              </div>
            </template>
          </q-field>
          <q-field outlined label="Comensales" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.diners }}
              </div>
            </template>
          </q-field>
          <q-field outlined label="Numero de mesa" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.table_number }}
              </div>
            </template>
          </q-field>
          <q-card-actions class="flex">
            <q-btn flat @click="deleteReservation(reservation.id)"
              class="bg-red text-white justify-end">Calcelar</q-btn>
          </q-card-actions>
        </div>
      </q-card-section>
      <q-card-section class="text-center flex" v-if="userReservations.length === 0">
        No se encontraron resultados.
      </q-card-section>
    </div>
  </q-page>
</template>


<script>
import { defineComponent, readonly, ref } from "vue";
import { LocalStorage, Notify } from "quasar";

export default defineComponent({
  name: "Home",
  data() {
    return {
      datos: null,
      data: {
        attributes: {
          full_name: "",
          password: "",
          tel_num: "",
        },
      },
      userReservations: [],
      text: ref(''),
      readonly: ref(false),
      disable: ref(false),
    };
  },
  created() {
    this.loadData();
    this.getUserReservations();
  },
  methods: {
    loadData() {
      const userData = LocalStorage.getItem("data");
      if (userData) {
        this.datos = userData;
        this.data.attributes.email = userData.email;
        this.data.attributes.full_name = userData.full_name;
        this.data.attributes.password = userData.password;
        this.data.attributes.tel_num = userData.tel_num;
      } else {
        this.redirectUnathorized();
      }
    },
    redirectUnathorized() {
      window.location.href = "/unathorized";
    },
    getUserReservations() {
      let user_id = LocalStorage.getItem("usrid"); // Cambiar a petición para comprobar token
      let token = LocalStorage.getItem("token");

      fetch(
        "http://booknow_api.randion.es/api/v1/reservations?filter[user_id]=" +
        user_id,
        {
          headers: {
            Accept: "application/vnd.api+json",
            Authorization: `Bearer ${token}`,
          },
        }
      )
        .then((res) => res.json())
        .then((response) => {
          console.log(response);
          this.userReservations = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteReservation(reservation_id) {
      let token = LocalStorage.getItem("token");
      console.log(token);

      fetch("http://booknow_api.randion.es/api/v1/reservations/" + reservation_id, {
        headers: {
          Accept: "application/vnd.api+json",
          Authorization: `Bearer ${token}`,
        },
        method: "DELETE",
      })
        .then(() => {
          Notify.create({
            message: "Reserva cancelada!",
            type: "positive",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
});
</script>
