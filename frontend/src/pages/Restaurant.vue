<template>
  <q-page-container style="padding-top: 0%">
    <q-page>
      <div class="max-width">
        <div class="col">
          <div>
            <q-carousel
              v-model="slide"
              height="250px"
            >
              <q-carousel-slide :name="1" img-src="https://cdn.quasar.dev/img/mountains.jpg" />
            </q-carousel>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="row mulish title">
            <h3 class="q-ma-md">{{restaurant.name}}</h3>
          </div>
          <div class="row">
            <q-btn class="q-ma-sm bg-primary text-white" :label="restaurant.category" icon="restaurant"></q-btn>
            <q-btn class="q-ma-sm bg-primary text-white" :label="restaurant.location" icon="location_on"></q-btn>
          </div>
          <div class="row q-ma-sm rounded-borders">
            <div class="col mulish">
              <h5 class="q-ma-md">Información de contacto</h5>
              <div class="row">
                <p>{{ restaurant.email }}</p>
              </div>
              <div class="row">
                <p>{{ restaurant.address }}</p>
              </div>
              <div class="row">
                <p>{{ restaurant.tel_num }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row q-pa-md bg-blue-2">
        <div class="col q-ma-md mulish">
          <div>
            <q-stepper
              v-model="step"
              ref="stepper"
              color="primary"
              animated

            >
              <q-step
                :name="1"
                title="DIA"
                :caption="reservation.date"
                icon="calendar_today"
                :done="step > 1 && reservation.date != ''"
              >
                <div class="row justify-center">
                  <div class="q-pa-md">
                    <q-date
                      v-model="reservation.date"
                      landscape
                      :options="optionsFn"
                      navigation-min-year-month="2024/04"
                      navigation-max-year-month="2024/06"
                    />
                  </div>
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn v-if="reservation.date != '' " @click="$refs.stepper.next()" color="primary" :label="step === 4 ? 'Finish' : 'Continue'" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>
              </q-step>

              <q-step
                :name="2"
                title="HORA"
                :caption="reservation.hour"
                icon="schedule"
                :done="step > 2"
              >
                <div class="row q-ma-md" v-for="horario in this.horarios" :key="horario">
                  <div class="col">
                    <div class="row justify-center">
                      <h6 class="rounded-borders q-ma-sm q-pa-sm ">{{ horario[0]}}</h6>
                    </div>
                    <div class="row justify-center card-body">
                      <q-btn v-for="hora in horario[1]" @click="updateHour(hora)" :active="reservation.hour === hora" :key="hora" >{{ hora }}</q-btn>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn v-if="reservation.hour != '' " @click="$refs.stepper.next()" color="primary" :label="step === 4 ? 'Finish' : 'Continue'" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>
              </q-step>

              <q-step
                :name="3"
                title="COMENSALES"
                :caption="reservation.diners"
                icon="group"
                :done="step > 3"
              >
                <div class="row justify-center card-body">
                  <q-btn  @click="setDiners(diners_num)" class="q-ma-sm boton" v-for="diners_num in diners_arr" :label="diners_num" :key="diners_num"></q-btn>
                </div>
                <q-stepper-navigation>
                  <q-btn v-if="reservation.diners != '' " @click="$refs.stepper.next()" color="primary" :label="step === 4 ? 'Finish' : 'Continue'" />
                  <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
                </q-stepper-navigation>

              </q-step>

              <q-step
                :name="4"
                title="MESA RESERVA"
                :caption="reservation.table_number"
                icon="table_restaurant"
              >
                <div class="row justify-center">
                  <q-btn label="Selecionar Mesa" color="blue-9" @click="dialog = true" />
                </div>
                <div class="row">
                  <q-stepper-navigation>
                    <q-btn  @click="makeReservation()" color="primary" :label="step === 4 ? 'Finish' : 'Continue'" />
                    <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
                  </q-stepper-navigation>
                </div>

              </q-step>
            </q-stepper>
          </div>
        </div>
      </div>
      <q-dialog
        v-model="dialog"
        persistent
        :maximized="maximizedToggle"
        transition-show="slide-up"
        transition-hide="slide-down"
      >
        <q-card class="bg-primary max-height">
          <q-card-section>
            <SvgComponent svg-height="500px" svg-width="100%" :hour="reservation.hour" :day="reservation.date" :diners="reservation.diners"></SvgComponent>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- ! Confirmation card -->
      <q-dialog v-model="card">
        <q-card class="my-card mulish">
          <q-card-section>
            <h4 class="q-ma-2">CONFIRMACIÓN RESERVA</h4>
          </q-card-section>
          <q-separator />
          <q-card-section class="column items-center">
            <div class="column items-center">
              <h6 class="q-ma-sm text-primary">Dia Reserva</h6>
              <p class="q-ml-sm">{{reservation.date}}</p>
            </div>
            <div class="column items-center">
              <h6 class="q-ma-sm  text-primary">Hora Reserva</h6>
              <p class="q-ml-sm">{{reservation.hour}}</p>
            </div>
            <div class="column items-center">
              <h6 class="q-ma-sm  text-primary">Nº de Comensales</h6>
              <p class="q-ml-sm">{{reservation.diners}}</p>
            </div>
            <div class="column items-center">
              <h6 class="q-ma-sm  text-primary">Nº de Mesa</h6>
              <p class="q-ml-sm">{{reservation.table_number}}</p>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <q-btn @click="confirmReservation()" flat color="primary" label="Confirmar" :loading="loading"/>
            <q-btn v-close-popup flat color="red" round icon="close" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page>
  </q-page-container>
</template>
<script>
import moment from "moment";
import FooterComponent from "../components/FooterComponent.vue";
import ResFormComponent from "src/components/ResFormComponent.vue";
import SvgComponent from "src/components/SvgComponent.vue";
import { LocalStorage, Notify } from "quasar";

export default {
  name: "RestaurantPage",
  components: {
    FooterComponent,
    ResFormComponent,
    SvgComponent
  },
  data() {
    return {
      restaurant: {},
      slide: "first",
      slide: 1,
      step : 1,
      diners_arr : ["1","2","3","4","5","6","7","8","9","10","11","12"],
      reservation : {
        date : "",
        reservation_code : "",
        hour : "",
        diners :"",
        table_number : "",
        user_id : LocalStorage.getItem('usrid'),
        restaurant_id : parseInt(this.$route.query.id),
        design_id : 0
      },
      today : "",
      dialog : false,
      maximizedToggle: true,
      horarios : [],
      card: false,
      loading : false

    };
  },
  mounted() {
      let today = moment().format("L").split("/");
      this.today = today[2] + "/" + today[0] + "/" + today[1];

      this.reservation.date = today[2] + "/" + today[0] + "/" + today[1];
    },
  beforeCreate() {

    fetch("http://booknow_api.randion.es/api/v1/restaurants/" + this.$route.query.id, {
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


        for(let tourn in this.tourns){

          let horas_int = []

          console.log(this.tourns[tourn].start,this.tourns[tourn].end);

          let split_start = this.tourns[tourn].start.split(':')
          let split_end = this.tourns[tourn].end.split(':')
          let start = parseInt(split_start[0]) * 60 + parseInt(split_start[1])
          let end = parseInt(split_end[0]) * 60 + parseInt(split_end[1])
          console.log(start,end);

          horas_int.push(start)

          while(start <= end){

            start = start + intervalo
            if(start > end){

              continue
            }
            horas_int.push(start)


          }

          let aux_arr = []

          for(let i = 0;i < horas_int.length;i++){

            let horas = Math.floor(horas_int[i] / 60)
            let minutos = horas_int[i] % 60

            let fecha = new Date();
            fecha.setHours(horas,minutos,0,0)

            let horaFormateada = fecha.toLocaleTimeString('es-ES', {hour: '2-digit',minute:'2-digit'})

            aux_arr.push(horaFormateada)

            // console.log(horaFormateada);
          }
          this.horarios.push([this.tourns[tourn].tourn_name,aux_arr])

        }

        console.log(this.horarios);
      }
    );
  },
  methods : {

    optionsFn (date) {
        return date >= this.today
    },
    setDiners(num){

      this.reservation.diners = num

    },
    getAviabletables(date,hour,diners){

      console.log(date);

      fetch("http://booknow_api.randion.es/api/v1/reservations?filter[restaurant_id]=" + this.$route.query.id + "&filter[date]=" + date, {
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
    updateHour(hora){

      this.reservation.hour = hora

    },
    makeReservation(){

      let reservation_code = Math.floor(Math.random() * (999999999999 - 100000000000 + 1)) + 100000000000
      this.reservation.reservation_code = reservation_code.toString()
      this.reservation.design_id = parseInt(LocalStorage.getItem('dsid'))
      this.reservation.table_number = LocalStorage.getItem('table')

      console.log(this.reservation);

      this.card = true

    },
    confirmReservation(){


      if(LocalStorage.getItem('token') == null){

        setTimeout(() => {

          this.$router.push("/login")
        },1500)
        Notify.create({

          message : "Inicia sessión para continuar",
          type : "alert"

        })
      }else {

        if(LocalStorage.getItem('table') == null){

          Notify.create({

            message: "Selecciona una mesa",
            type: "negative"

          })

        } else {

          this.loading = true
          let token = LocalStorage.getItem('token')

          fetch("http://booknow_api.randion.es/api/v1/reservations",{
            method : 'POST',
            headers : {

              'Accept' : "application/vnd.api+json",
              'Content-Type' : "application/vnd.api+json",
              'Authorization' : `Bearer ${token}`
            },
            body : JSON.stringify({data : { attributes : this.reservation}})
          }).then((res) => res.json()).then((response) => {

            console.log(response);
            this.loading = false
            this.card = false

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
.boton{

  width: 20%;

}
.title{

  border-bottom: 2px black solid;

}
.card-body q-btn:hover{

  background-color: rgb(77, 77, 243);

}

</style>
