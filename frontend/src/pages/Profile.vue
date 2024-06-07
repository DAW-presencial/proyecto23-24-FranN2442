<template>
  <div>
    <div class="q-pa-md q-gutter-sm bg-grey-2 flex flex-center mgTop4 font-lato">
      <q-breadcrumbs>
        <q-breadcrumbs-el label="Home" icon="home" to="/" @click="handleBreadcrumbClick('home')" />
        <q-breadcrumbs-el :label="$t('profileIcon')" icon="person" to="/profile"
          @click="handleBreadcrumbClick('person')" />
      </q-breadcrumbs>
    </div>
    <q-page class="bg-grey-2 flex flex-center font-lato">
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
              <q-btn class="btn" style="margin-left: 0px;" :label="$t('saveBtn')" color="green"
                @click="updateProfile" />
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
              <q-btn flat @click="deleteReservationHour(reservation)" class="bg-red text-white justify-end">{{
                $t('reserveBtn')
                }}</q-btn>
            </q-card-actions>
          </div>
        </q-card-section>
        <q-card-section class="text-center" v-if="userReservations.length === 0">
          <div class="text-caption" style="font-size: 15px;">{{ $t('noReserve') }}</div>
        </q-card-section>
      </div>
      <FooterComponent></FooterComponent>
    </q-page>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { LocalStorage, Notify } from "quasar";
import FooterComponent from '../components/FooterComponent.vue';
import { apiUrl } from 'boot/axios';
import { useI18n } from 'vue-i18n';
import moment from "moment";

export default defineComponent({
  name: "Home",
  components: {
    FooterComponent
  },
  setup() {
    const { t } = useI18n();
    return { t };
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
      confirm_pass: '',
      todayString: '',
      usrid: ""
    };
  },
  created() {
    this.usrid = LocalStorage.getItem("usrid");

    this.loadData();
    this.getUserReservations();
    let today = moment().format("L").split("/");
    this.todayString = today[2] + "/" + today[0] + "/" + today[1];
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
      let token = LocalStorage.getItem("token");

      fetch(
        apiUrl + "/reservations?filter[user_id]=" + this.usrid,
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
    deleteReservationHour(reservation) {

      let token = LocalStorage.getItem("token");

      if(this.todayString == reservation.attributes.date){

        fetch(apiUrl + "/designs/" + reservation.attributes.design_id , {
        headers: {
          "Accept" : "application/vnd.api+json",
          "Content-Type" : "application/vnd.api+json",
          Authorization: `Bearer ${token}`,
        },
        method: "PATCH",
        body: JSON.stringify({

          table: reservation.attributes.table_number,
          hour : reservation.attributes.hour

        })
        }).then((res) => res.json()).then((response) => {

          console.log(response);

          this.deleteReservation(reservation.id)


        }).catch((error) => {
          console.log(error);
        });
      } else {

        this.deleteReservation(reservation.id)

      }

    },
    updatePassword() {
      if (!this.current_pass || !this.new_pass || !this.confirm_pass) {
        Notify.create({
          message: this.t('errorUpdatingProfile'),
          type: 'negative'
        });
        return;
      }

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
              message: this.t('passwordUpdated'),
              type: 'positive'
            });
          } else {
            throw new Error(this.t('errorUpdatingPassword'));
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
          message: this.t('passwordMismatch'),
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
      }).then((res) => res.json()).then(response => {

        Notify.create({
          message: this.t('profileUpdated'),
          type: 'positive'
        });
        this.data.full_name = response.data.attributes.full_name
        this.data.email = response.data.attributes.email
        this.data.tel_num = response.data.attributes.tel_num

        LocalStorage.set('data', this.data)

      }).catch(error => {
          Notify.create({
            message: this.t('errorUpdatingProfile'),
            type: 'negative'
          });
          throw new Error(this.t('errorUpdating'));

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
    },
    deleteReservation(reservation_id){

      let token = LocalStorage.getItem("token");
      
      fetch(apiUrl + "/reservations/" + reservation_id, {
        headers: {
          Accept: "application/vnd.api+json",
          Authorization: `Bearer ${token}`,
        },
        method: "DELETE",
      }).then((res) => {

        Notify.create({

          message: "Reserva Eliminada",
          type: "positive"
        })

        console.log(res);

      }).catch((error) => {
        console.log(error);
      });

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
  height: max-content;
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
