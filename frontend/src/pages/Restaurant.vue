<template>
  <q-page-container style="padding-top: 0%">
    <q-page>
      <div class="example-row-equal-width">
        <div class="row">
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
          <div class="col-6 q-pa-md">
            <div class="row mulish">{{restaurant.name}}</div>
            <div class="row">{{restaurant}}</div>
          </div>
          <div class="col-6 q-pa-md">
            <div class="row justify-center q-ma-md mulish">
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
                    caption="Dia de asistencia"
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
                    caption="Hora de llegada"
                    icon="schedule"
                    :done="step > 2"
                  >
                    <div class="row q-ma-md" v-for="horario in this.horarios" :key="horario">
                      <div class="col">
                        <div class="row">
                          <h6 style="margin: 1%">{{ horario[0]}}</h6>
                        </div>
                        <div class="row">
                          <q-btn v-for="hora in horario[1]" :key="hora" v-model="reservation.hour" :label="hora"></q-btn>
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
                    caption="Nº de asistentes"
                    icon="group"
                    :done="step > 3"
                  >
                    <div class="row justify-center">
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
                    caption="Elija una mesa"
                    icon="table_restaurant"
                  >

                    <q-btn label="Selecionar Mesa" color="primary" @click="dialog = true" />

                    <q-stepper-navigation>
                      <q-btn v-if="reservation.diners != '' " @click="$refs.stepper.next()" color="primary" :label="step === 4 ? 'Finish' : 'Continue'" />
                      <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
                    </q-stepper-navigation>

                  </q-step>
                </q-stepper>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="bg-blue-1">
        <div style="max-height: 600px; height: 600px; width: 100%;display: flex;flex-wrap: wrap;">
          <div style="height: 100%;width: 65%;z-index: 1;flex: 1 0 10rem;">
            <div class="rounded-borders shadow-2 bg-white" style="height: 700px;margin: 2%;padding: 1%">
              <div>
                <h3 class="q-pa-md mulish" style="margin: 5px;border-bottom: 2px black solid;">{{restaurant.name}}</h3>
              </div>
              <div class="rounded-borders">
                <div class="q-pa-sm" style="display: flex;flex-wrap: wrap">
                  <q-btn color="white" icon="pin_drop" text-color="black" class="shadow-1 q-ma-sm mulish " :label="restaurant.address" />
                  <q-btn color="white" icon="restaurant" text-color="black" class="shadow-1 q-ma-sm mulish " :label="restaurant.location" />
                </div>
                <h5 style="margin: 0%;" class="q-pa-sm mulish text-primary">Información</h5>
                <div class="q-pa-sm">
                  <q-list >
                    <q-item class="mulish q-ma-sm rounded-borders " v-ripple>
                      <q-item-section class="rest-item  q-pa-sm">
                        <q-item-label overline>EMAIL</q-item-label>
                        <q-item-label>{{restaurant.email}}</q-item-label>
                      </q-item-section>
                    </q-item>

                    <q-item v-ripple class="mulish q-ma-sm rounded-borders ">
                      <q-item-section class="rest-item q-pa-sm">
                        <q-item-label overline>NUMERO TELEFONO</q-item-label>
                        <q-item-label>{{restaurant.tel_num}}</q-item-label>
                      </q-item-section>
                    </q-item>

                    <q-item v-ripple class="mulish q-ma-sm rounded-borders ">
                      <q-item-section class="rest-item q-pa-sm">
                        <q-item-label overline>WEB</q-item-label>
                        <q-item-label><a href="{{restaurant.web_url}}">{{restaurant.web_url}}</a></q-item-label>
                      </q-item-section>
                    </q-item>
                  </q-list>
                </div>
              </div>
            </div>
          </div>
          <div style="height: 100%;width: 35%;z-index: 1;flex: 1 0 10rem;">
            <div class="rounded-borders shadow-5 bg-white" style=" height: 610px;margin: 4%;">
              <div class="flex justify-center">
                <h4 class="mulish">HORARIO</h4>
              </div>
              <div class="q-pa-md">
                <q-list bordered class="rounded-borders" v-for="dia in restaurant.opening_hours" :key="dia" >
                  <q-expansion-item
                      expand-separator
                      group="horario"
                      header-class="bg-blue-3 text-black mulish"
                      icon="event"
                      :label="dia[0]"
                      :caption="dia[1]"
                      dense-toggle
                      v-if="dia[1] == 'Abierto'">
                    <q-card>
                      <q-card-section class="flex justify-center mulish">
                        {{dia[2]}} - {{ dia[3] }}
                      </q-card-section>
                    </q-card>
                  </q-expansion-item>
                  <q-expansion-item
                      expand-separator
                      header-class="bg-red-3 text-black"
                      group="horario"
                      icon="event_busy"
                      :label="dia[0]"
                      :caption="dia[1]"
                      disable
                      v-else>
                    <q-card>
                      <q-card-section class="flex justify-center">
                        Cerrado
                      </q-card-section>
                    </q-card>
                  </q-expansion-item>
                </q-list>
              </div>
            </div>
          </div>
        </div>
        <q-parallax :height="400" src="../assets/plato.jpg" style="z-index: 0;">

        </q-parallax>
        <div class="bg-blue-1" style="height: auto;width: 100%;">
          <div class=" mulish">
            <div  class="bg-primary text-white shadow-2" style="height: 15%;width: 100%;">
              <h4 class="q-pa-md" style="margin: 0px">RESERVA YA!</h4>
            </div>
            <div class="q-pa-xl">

              <ResFormComponent></ResFormComponent>
            </div>
          </div>
        </div>
      </div> -->
      <!-- <FooterComponent></FooterComponent> -->
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
    </q-page>
  </q-page-container>
</template>
<script>
import moment from "moment";
import FooterComponent from "../components/FooterComponent.vue";
import ResFormComponent from "src/components/ResFormComponent.vue";
import SvgComponent from "src/components/SvgComponent.vue";

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
      diners_arr : [1,2,3,4,5,6,7,8,9,10,11,12],
      reservation : {
        date : "",
        hour : "11:00",
        diners :"",
        table : ""
      },
      today : "",
      dialog : false,
      maximizedToggle: true,
      horarios : []

    };
  },
  mounted() {
      let today = moment().format("L").split("/");
      this.today = today[2] + "/" + today[0] + "/" + today[1];

      this.reservation.date = today[2] + "/" + today[0] + "/" + today[1];
    },
  beforeCreate() {

    fetch("http://booknow.randion.es/api/v1/restaurants/" + this.$route.query.id, {
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

      fetch("http://booknow.randion.es/api/v1/reservations?filter[restaurant_id]=" + this.$route.query.id + "&filter[date]=" + date, {
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

    }
  }
};
</script>
<style scoped>
.boton{

  width: 20%;

}
</style>
