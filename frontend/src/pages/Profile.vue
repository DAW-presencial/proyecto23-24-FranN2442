<template>
  <div class="q-pa-md q-gutter-sm bg-grey-2 flex flex-center mgTop4 my-font2">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Home" icon="home" to="/" />
      <q-breadcrumbs-el :label="$t('profileIcon')" icon="person" to="/profile" />
    </q-breadcrumbs>
  </div>
  <q-page class="bg-grey-2 flex flex-center my-font2">
    <div class="q-pa-md shadow-2 my_card bg-white container mgTop4">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 500px">
          <div class="text-h5 text-weight-bold text-left">{{ $t('profileTitle') }}</div>
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('profileName') }}</div>
          <q-input v-model="this.data.attributes.full_name" outlined />
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('profilePhone') }}</div>
          <q-input v-model="this.data.attributes.tel_num" outlined />
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('profileMail') }}</div>
          <q-input v-model="this.data.attributes.email" outlined />
          <div class="text-left q-gutter-md">
            <q-btn class="btn" style="margin-left: 0px;" :label="$t('saveBtn')" color="green" @click="updateProfile" />
          </div>
        </div>
      </q-card-section>
    </div>
    <div class="q-pa-md shadow-2 my_card bg-white container">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 500px">
          <div class="text-h5 text-weight-bold text-left">{{ $t('updatePass') }}</div>
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('currentPass') }}</div>
          <q-input v-model="current_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" />
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('newPass') }}</div>
          <q-input v-model="new_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" />
          <div class="text-left text-caption" style="font-size: 15px;">{{ $t('confirmPass') }}</div>
          <q-input v-model="confirm_pass" outlined
            :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'La contraseña debe tener al menos 8 caracteres']"
            type="password" />
          <div class="text-left q-gutter-md">
            <q-btn class="btn" style="margin-left: 0px;" :label="$t('saveBtn')" color="green" @click="savePassword" />
          </div>
        </div>
      </q-card-section>
    </div>
    <div class="q-pa-md shadow-2 my_card bg-white container">
      <q-card-section>
        <div class="text-h5 text-weight-bold text-left">{{ $t('reserve') }}</div>
      </q-card-section>
      <q-card-section class="text-center flex" v-if="userReservations != []" style="padding: 0px;">
        <div class="q-pa-md" v-for="reservation in this.userReservations" :key="reservation.id">
          <q-field outlined :label="$t('reserveCode')" label-color="primary" stack-label class="q-ma-sm"
            style="width: 250px;">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.reservation_code }}
              </div>
            </template>
          </q-field>
          <q-field outlined :label="$t('reserveDate')" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.date }}
              </div>
            </template>
          </q-field>
          <q-field outlined :label="$t('reserveHour')" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.hour }}
              </div>
            </template>
          </q-field>
          <q-field outlined :label="$t('reserveDiners')" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.diners }}
              </div>
            </template>
          </q-field>
          <q-field outlined :label="$t('reserveTable')" label-color="primary" stack-label class="q-ma-sm">
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">
                {{ reservation.attributes.table_number }}
              </div>
            </template>
          </q-field>
          <q-card-actions class="flex">
            <q-btn flat @click="deleteReservation(reservation.id)"
              class="bg-red text-white justify-end">{{ $t('reserveBtn') }}</q-btn>
          </q-card-actions>
        </div>
      </q-card-section>
      <q-card-section class="text-center" v-if="userReservations.length === 0">
        <div class="text-caption" style="font-size: 15px;">{{ $t('noReserve') }}</div>
      </q-card-section>
    </div>
    <FooterComponent></FooterComponent>
  </q-page>
</template>

<script>
import { defineComponent } from "vue";
import { LocalStorage, Notify } from "quasar";
import FooterComponent from '../components/FooterComponent.vue'
import { apiUrl } from 'boot/axios'

export default defineComponent({
  name: "Home",
  components: {
    FooterComponent
  },
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
        apiUrl + "/reservations?filter[user_id]=" +
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

      fetch(apiUrl + "/reservations/" + reservation_id, {
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

      fetch(apiUrl + "/passwordReset/" + user_id, {
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
    },

    updateProfile() {
      let params = {
        "full_name": this.data.attributes.full_name,
        "tel_num": this.data.attributes.tel_num,
        "email": this.data.attributes.email
      };
      let user_id = LocalStorage.getItem("usrid");
      let token = LocalStorage.getItem("token");

      fetch(apiUrl + "/users/" + user_id, {
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
            Notify.create({
              message: 'Perfil actualizado, reinicia sesión',
              type: 'positive'
            });
          } else {
            Notify.create({
              message: 'Error al actualizar perfil',
              type: 'negative'
            });
            throw new Error('Error al actualizar');
          };
        })
        .catch(error => {
          console.error(error);
        });
    },
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
  height: 55vh;
  margin-top: 4vh;
  border-radius: 20px;
}

.mgTop4 {
  margin-top: 10px;
}

@media screen and (max-width: 600px) {
 .q-pa-md {
    padding: 10px;
  }

  .bg-grey-2 {
    background-color: #f5f5f5;
  }

  .container {
    width: 60%;
    max-width: none;
    margin: 4vh auto 0;
    border-radius: 20px;
    height: auto;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
  }

  .text-h5 {
    font-size: 18px;
  }

  .text-caption {
    font-size: 12px;
  }

  .q-field {
    margin-bottom: 10px;
  }

  .q-btn {
    margin-top: 10px;
    padding: 8px 16px;
  }
}
</style>
