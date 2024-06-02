<template>
  <q-page-container style="padding-top: 0%">
    <div class="col">
      <div>
        <q-carousel v-model="slide" height="250px">
          <q-carousel-slide :name="1" img-src="../assets/restaurant-bg.jpg" />
        </q-carousel>
      </div>
    </div>
    <q-page class="card-container font-lato">
      <div class="max-width">
      </div>
      <div style="width: 50%">
        <div class="col">
          <div class="row">
            <h3 class="q-ma-md noMargin">{{ restaurant.name }}</h3>
            <hr>
          </div>
          <div class="row q-gutter-md">
          </div>
          <div class="row q-ma-sm rounded-borders font-lato">
            <div class="col font-lato">
              <div class="row align-center">
                <i class="fa-solid fa-utensils icon-wrapper"></i>
                <p>{{ restaurant.category }}</p>
              </div>
              <div class="row align-center">
                <i class="fa-solid fa-location-dot icon-wrapper"></i>
                <p>{{ restaurant.location }}</p>
              </div>
              <div class="row align-center">
                <i class="fa-solid fa-envelope icon-wrapper"></i>
                <p>{{ restaurant.email }}</p>
              </div>
              <div class="row align-center">
                <i class="fa-solid fa-location-crosshairs icon-wrapper"></i>
                <p>{{ restaurant.address }}</p>
              </div>
              <div class="row align-center">
                <q-icon name="phone" class="icon-wrapper" />
                <p>{{ restaurant.tel_num }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Calendar-->
      <div class="row q-pa-md bg-blue-2 card-container" style="width: 50%;">
        <div class="col q-ma-md font-lato">
          <div>
            <q-stepper v-model="step" ref="stepper" color="primary" animated>
              <q-step :name="1" :title="$t('day')" :caption="reservation.date" icon="calendar_today"
                :done="step > 1 && reservation.date != ''">
                <div class="row justify-center">
                  <div class="q-pa-md">
                    <q-date v-model="reservation.date" landscape :options="optionsFn"
                      navigation-min-year-month="2024/04" navigation-max-year-month="2024/06" />
                  </div>
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn v-if="reservation.date != ''" @click="$refs.stepper.next()" color="primary"
                      :label="step === 4 ? $t('btnF') : $t('btnR')" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" :label="$t('btnB')"
                      class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>
              </q-step>
              <q-step :name="2" :title="$t('hour')" :caption="reservation.hour" icon="schedule" :done="step > 2">
                <div class="row q-ma-md" v-for="horario in this.horarios" :key="horario">
                  <div class="col">
                    <div class="row justify-center">
                      <h6 class="rounded-borders q-ma-sm q-pa-sm horario-title">{{ horario[0] }}</h6>
                    </div>
                    <div class="row justify-center card-body">
                      <q-btn v-for="hora in horario[1]" @click="updateHour(hora)" :active="reservation.hour === hora"
                        :key="hora" class="hora-btn">{{ hora }}</q-btn>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn v-if="reservation.hour != ''" @click="$refs.stepper.next()" color="primary"
                      :label="step === 4 ? $t('btnF') : $t('btnR')" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" :label="$t('btnB')"
                      class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>
              </q-step>
              <q-step :name="3" :title="$t('eaters')" :caption="reservation.diners" icon="group" :done="step > 3">
                <div class="row justify-center card-body">
                  <q-btn @click="setDiners(diners_num)" class="q-ma-sm boton diners-btn"
                    v-for="diners_num in diners_arr" :label="diners_num" :key="diners_num"></q-btn>
                </div>
                <q-stepper-navigation>
                  <q-btn v-if="reservation.diners != ''" @click="$refs.stepper.next()" color="primary"
                    :label="step === 4 ? $t('btnF') : $t('btnR')" />
                  <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" :label="$t('btnB')"
                    class="q-ml-sm" />
                </q-stepper-navigation>
              </q-step>

              <q-step :name="4" :title="$t('table')" :caption="reservation.table_number" icon="table_restaurant">
                <div class="row justify-center">
                  <q-btn :label="$t('chooseT')" color="blue-9" @click="dialog = true" class="mesa-btn" />
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn @click="makeReservation()" color="primary" :label="step === 4 ? $t('btnF') : $t('btnR')" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" :label="$t('btnB')"
                      class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>
              </q-step>
            </q-stepper>
          </div>
        </div>
      </div>
      <q-dialog v-model="dialog" persistent :maximized="maximizedToggle" transition-show="slide-up"
        transition-hide="slide-down">
        <q-card class="bg-primary max-height">
          <q-card-section class="text-center">
            <SvgComponent svg-height="500px" :hour="reservation.hour" :day="reservation.date"
              :diners="reservation.diners"></SvgComponent>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- ! Confirmation card -->
      <q-dialog v-model="card">
        <q-card class="my-card font-lato elevated-card">
          <q-card-section class="text-center bg-primary">
            <h4 class="q-ma-2 text-yellow font-lato">{{ $t('confR') }}</h4>
          </q-card-section>
          <q-separator />
          <q-card-section class="column items-center q-pa-lg">
            <div class="column items-center q-pa-sm q-gutter-sm">
              <h6 class="q-ma-none text-primary">{{ $t('dayR') }}</h6>
              <p class="q-ma-none">{{ reservation.date }}</p>
            </div>
            <div class="column items-center q-pa-sm q-gutter-sm">
              <h6 class="q-ma-none text-primary">{{ $t('hourR') }}</h6>
              <p class="q-ma-none">{{ reservation.hour }}</p>
            </div>
            <div class="column items-center q-pa-sm q-gutter-sm">
              <h6 class="q-ma-none text-primary">{{ $t('numD') }}</h6>
              <p class="q-ma-none">{{ reservation.diners }}</p>
            </div>
            <div class="column items-center q-pa-sm q-gutter-sm">
              <h6 class="q-ma-none text-primary">{{ $t('numT') }}</h6>
              <p class="q-ma-none">{{ reservation.table_number }}</p>
            </div>
          </q-card-section>
          <q-separator />
          <q-card-actions align="right" class="q-pa-sm">
            <q-btn @click="confirmReservation()" flat color="primary" :label="$t('confBtn')" :loading="loading" />
            <q-btn v-close-popup flat color="red" round icon="close" />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <FooterComponent></FooterComponent>
    </q-page>
  </q-page-container>
</template>
<script>
import moment from "moment";
import FooterComponent from "../components/FooterComponent.vue";
import ResFormComponent from "src/components/ResFormComponent.vue";
import SvgComponent from "src/components/SvgComponent.vue";
import { LocalStorage, Notify } from "quasar";
import { apiUrl } from 'boot/axios'
import { useI18n } from 'vue-i18n';

export default {
  name: "RestaurantPage",
  components: {
    FooterComponent,
    ResFormComponent,
    SvgComponent
  },
  setup() {
    const { t } = useI18n();
    return { t };
  },
  data() {
    return {
      restaurant: {},
      slide: "first",
      slide: 1,
      step: 1,
      diners_arr: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
      reservation: {
        date: "",
        reservation_code: "",
        hour: "",
        diners: "",
        table_number: "",
        user_id: LocalStorage.getItem('usrid'),
        restaurant_id: parseInt(this.$route.query.id),
        design_id: 0
      },
      today: "",
      dialog: false,
      maximizedToggle: true,
      horarios: [],
      card: false,
      loading: false

    };
  },
  mounted() {
    let today = moment().format("L").split("/");
    this.today = today[2] + "/" + today[0] + "/" + today[1];

    this.reservation.date = today[2] + "/" + today[0] + "/" + today[1];
  },
  beforeCreate() {

    fetch(apiUrl + "/restaurants/" + this.$route.query.id, {
      method: "GET",
      headers: {
        Accept: "application/vnd.api+json",
      },
    })
      .then((res) => res.json())
      .then((resultado) => {
        this.restaurant = resultado.data.attributes;

        this.tourns = JSON.parse(this.restaurant.tourns)

        let intervalo = 30 // Meter valor en la base de datos


        for (let tourn in this.tourns) {

          let horas_int = []

          console.log(this.tourns[tourn].start, this.tourns[tourn].end);

          let split_start = this.tourns[tourn].start.split(':')
          let split_end = this.tourns[tourn].end.split(':')
          let start = parseInt(split_start[0]) * 60 + parseInt(split_start[1])
          let end = parseInt(split_end[0]) * 60 + parseInt(split_end[1])
          console.log(start, end);

          horas_int.push(start)

          while (start <= end) {

            start = start + intervalo
            if (start > end) {

              continue
            }
            horas_int.push(start)


          }

          let aux_arr = []

          for (let i = 0; i < horas_int.length; i++) {

            let horas = Math.floor(horas_int[i] / 60)
            let minutos = horas_int[i] % 60

            let fecha = new Date();
            fecha.setHours(horas, minutos, 0, 0)

            let horaFormateada = fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })

            aux_arr.push(horaFormateada)

            // console.log(horaFormateada);
          }
          this.horarios.push([this.tourns[tourn].tourn_name, aux_arr])

        }

        console.log(this.horarios);
      }
      );
  },
  methods: {

    optionsFn(date) {
      return date >= this.today
    },
    setDiners(num) {

      this.reservation.diners = num

    },
    getAviabletables(date, hour, diners) {

      console.log(date);

      fetch(apiUrl + "/reservations?filter[restaurant_id]=" + this.$route.query.id + "&filter[date]=" + date, {
        method: "GET",
        headers: {
          Accept: "application/vnd.api+json",
        },
      })
        .then((res) => res.json())
        .then((resultado) => {
          let reservationsFiltred = resultado.data;
          console.log(reservationsFiltred);
        });


    },
    updateHour(hora) {

      this.reservation.hour = hora

    },
    makeReservation() {

      let reservation_code = Math.floor(Math.random() * (999999999999 - 100000000000 + 1)) + 100000000000
      this.reservation.reservation_code = reservation_code.toString()
      this.reservation.design_id = parseInt(LocalStorage.getItem('dsid'))
      const tableNumber = LocalStorage.getItem('table');
      if (!tableNumber || isNaN(parseInt(tableNumber))) {
        Notify.create({
          message: this.t('selectTable'),
          type: "negative"
        });
        return;
      }
      this.reservation.table_number = LocalStorage.getItem('table')

      console.log(this.reservation);

      this.card = true

    },
    confirmReservation() {


      if (LocalStorage.getItem('token') == null) {

        setTimeout(() => {

          this.$router.push("/login")
        }, 1500)
        Notify.create({

          message: this.t('logN'),
          type: "warning"

        })
      } else {

        if (LocalStorage.getItem('table') == null) {

          Notify.create({

            message: this.t('selectTable'),
            type: "negative"

          })

        } else {

          this.loading = true
          let token = LocalStorage.getItem('token')

          fetch(apiUrl + "/reservations", {
            method: 'POST',
            headers: {

              'Accept': "application/vnd.api+json",
              'Content-Type': "application/vnd.api+json",
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ data: { attributes: this.reservation } })
          }).then((res) => res.json()).then((response) => {

            console.log(response);
            this.loading = false
            this.card = false

            LocalStorage.remove('dsid')
            LocalStorage.remove('table')

            Notify.create({

              message: this.t('reserveOk'),
              type: "positive"

            })

          }).catch((error) => {

            console.log(error);

          })


        }
      }

    }
  }
};
</script>
<style scoped>
.boton {

  width: 20%;

}

.title {

  border-bottom: 2px black solid;

}

.card-body q-btn:hover {

  background-color: rgb(77, 77, 243);

}

.noMargin {
  margin-left: 0;
}

.card-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background-color: #f0f0f0;
  border-radius: 25%;
  color: #1976D2;
  margin-right: 10px;
  margin-top: 5px;
  margin-bottom: 5px;
}

.icon-wrapper .q-icon,
.icon-wrapper .fa-solid {
  font-size: 24px;
}

.align-center {
  display: flex;
  align-items: center;
}

p {
  margin: 0;
  font-size: 16px;
}

.card-container {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 16px;
}

.horario-title {
  background-color: #f0f0f0;
  color: #333;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 16px;
}

.hora-btn {
  background-color: #1e88e5;
  color: #ffffff;
  margin: 4px;
  border-radius: 8px;
  text-transform: uppercase;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.hora-btn:hover {
  background-color: #1565c0;
}

.diners-btn {
  background-color: #00897b;
  color: #ffffff;
  margin: 4px;
  border-radius: 8px;
  text-transform: uppercase;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.diners-btn:hover {
  background-color: #00695c;
}

.mesa-btn {
  background-color: #d84315;
  color: #ffffff;
  border-radius: 8px;
  text-transform: uppercase;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.mesa-btn:hover {
  background-color: #bf360c;
}

.q-stepper {
  background-color: #f9f9f9;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.q-stepper-navigation .q-btn {
  margin: 8px;
}

.q-step {
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: 16px;
  margin-bottom: 16px;
}

.q-step:last-child {
  border-bottom: none;
  margin-bottom: 0;
}

.q-btn {
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  text-transform: uppercase;
  border-radius: 8px;
  transition: background-color 0.3s, box-shadow 0.3s;
}

.q-btn:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.q-btn {
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  text-transform: uppercase;
  border-radius: 8px;
  transition: background-color 0.3s, box-shadow 0.3s;
  padding: 10px 20px;
}

.q-btn:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.elevated-card {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  border-radius: 12px;
  overflow: hidden;
  background-color: #ffffff;
}

.header-section {
  background-color: #343a40;
  color: white;
  padding: 16px;
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

.details-section {
  background-color: #f9f9f9;
  padding: 16px;
}

.action-section {
  background-color: #f0f0f0;
  border-top: 1px solid #ddd;
  padding: 16px;
  text-align: right;
}

.q-card h4 {
  font-size: 1.75rem;
  font-weight: bold;
  margin: 0;
  padding-bottom: 16px;
  color: #343a40;
}

.q-item-label {
  font-size: 1rem;
  font-weight: bold;
  color: #343a40;
}

.q-item-label[caption] {
  font-size: 0.875rem;
  color: #666;
}
</style>
