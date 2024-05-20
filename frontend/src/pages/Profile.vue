<template>
  <q-page class="bg-grey-2 flex flex-center">
    <div class="q-pa-md shadow-2 my_card bg-white container">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 500px">
          <div class="text-h5 text-weight-bold text-left">Información de perfil</div>
          <div class="text-left text-caption" style="font-size: 15px;">Nombre</div>
          <q-input v-model="this.data.attributes.full_name" outlined />
          <div class="text-left text-caption" style="font-size: 15px;">Teléfono</div>
          <q-input v-model="this.data.attributes.tel_num" outlined />
          <div class="text-left text-caption" style="font-size: 15px;">Correo electrónico</div>
          <q-input v-model="this.data.attributes.email" outlined />
          <div class="text-left q-gutter-md">
            <q-btn class="btn" style="margin-left: 0px;" label="Guardar" color="green" />
          </div>
        </div>
      </q-card-section>
    </div>
    <div class="q-pa-md shadow-2 my_card bg-white container">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 500px">
          <div class="text-h5 text-weight-bold text-left">Actualizar contraseña</div>
          <div class="text-left text-caption" style="font-size: 15px;">Contraseña actual</div>
          <q-input v-model="current_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" label="Password" />
          <div class="text-left text-caption" style="font-size: 15px;">Nueva Contraseña</div>
          <q-input v-model="new_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" label="Password" />
          <div class="text-left text-caption" style="font-size: 15px;">Confirmar Contraseña</div>
          <q-input v-model="confirm_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" label="Password" />
          <div class="text-left q-gutter-md">
            <q-btn class="btn" style="margin-left: 0px;" label="Guardar" color="green" @click="savePassword" />
          </div>
        </div>
      </q-card-section>
    </div>
    <div class="q-pa-md shadow-2 my_card bg-white container">
      <q-card-section>
        <div class="text-h5 text-weight-bold text-left">Reservas</div>
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
      <q-card-section class="text-center" v-if="userReservations.length === 0">
        <div class="text-caption" style="font-size: 15px;">No se encontraron reservas</div>
      </q-card-section>
    </div>
  </q-page>
</template>


<script>
import { defineComponent } from "vue";
import { LocalStorage, Notify } from "quasar";

export default defineComponent({
  name: "Home",
  data() {
    return {
      datos: null,
      data: {
        attributes: {
          full_name: "",
          tel_num: "",
          email: ""
        },
      },
      userReservations: [],
      current_pass: '',
      new_pass: '',
      confirm_pass: ''
    };
  },
  created() {
    this.loadData();
    this.getUserReservations();
    console.log(this.current_pass)
  },
  methods: {
    loadData() {
      const userData = LocalStorage.getItem("data");
      if (userData) {
        this.datos = userData;
        this.data.attributes.full_name = userData.full_name;
        this.data.attributes.tel_num = userData.tel_num;
        this.data.attributes.email = userData.email;
      } else {
        this.redirectUnathorized();
      }
    },
    redirectUnathorized() {
      window.location.href = "/unathorized";
    },
    getUserReservations() {
      let user_id = LocalStorage.getItem("usrid");
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

          setTimeout(() => {

            location.reload()

          },2000)
        })
        .catch((error) => {
          console.log(error);
        });
    },

    updatePassword() {
      let params = {
        "old_password": this.current_pass,
        "new_password": this.confirm_pass,
      };
      let user_id = LocalStorage.getItem("usrid");
      let token = LocalStorage.getItem("token");

      fetch("http://booknow_api.randion.es/api/v1/passwordReset/" + user_id, {
        method: 'PATCH',
        headers: {
          'Accept': 'application/vnd.api+json',
          'Content-Type': 'application/vnd.api+json',
          'Authorization': "Bearer " + token
        },
        body: JSON.stringify({ data: { type: 'users', attributes: params } })
      })
        .then(response => {
          if (response.ok) {
            console.log(response);
            Notify.create({
              message: 'Contraseña actualizada',
              type: 'positive'
            });
          } else {
            throw new Error('Error al actualizar la contraseña');
          }
        })
        .catch(error => {
          console.error(error);
        });
    },

    savePassword() {
      if (this.new_pass === this.confirm_pass) {
        this.updatePassword();
      } else {
        Notify.create({
          message: 'Error al actualizar la contraseña',
          type: 'negative'
        });
      }
    }
  },
});
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  justify-content: left;
  align-items: left;
  width: 50vw;
  height: 60vh;
  margin-top: 4vh;
  border-radius: 20px;
}
</style>
