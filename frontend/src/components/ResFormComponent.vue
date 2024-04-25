<template>
  <q-form class="full-height full-width q-gutter-md">
    <div style="margin: 0%">
      <!-- ! Nombre Completo -->
      <div class="q-ma-md">
        <q-input                 
          v-model="reservation.fullname"
          label="Nombre Completo"
          class="rounded-borders  q-ma-xs q-pl-xs shadow-2"
          label-color="black"
          label-for="fullname"
        />
      </div>
      <!-- ! Correo electrónico y Teléfono -->
      <div class="flex-wrap q-ma-md">
        <q-input
          v-model="reservation.email"
          class="rounded-borders q-pl-xs q-ma-xs shadow-2 flex-item"
          label-color="black"
          label="Correo electrónico"
          label-for="email"
        />
        <q-input
          v-model="reservation.tel"
          label="Teléfono"
          label-for="tel"
          label-color="black"
          class="rounded-borders q-pl-xs q-ma-xs shadow-2 flex-item"           
        />
      </div>
      <!-- ! Calendario + hora -->
      <div class="q-ma-md flex-wrap">
        <q-input
          v-model="reservation.date"
          mask="date"
          label="Fecha"
          label-for="date"
          class="rounded-borders q-ma-xs shadow-2 calendar"
          style="padding: 0%;margin: 0%;"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="reservation.date">
                  <div class="row items-center justify-end">
                    <q-btn
                      v-close-popup
                      label="Cerrar"
                      color="primary"
                      flat
                    />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      
        <q-input
          v-model="reservation.hour"
          class="rounded-borders q-pl-xs shadow-2 hora q-ma-xs"
          label="Hora"
          label-for="hour"
          label-color="black"
          readonly
        ></q-input>
        <q-btn
          label="Horas disponibles"
          color="white"
          @click="alert = true"
          flat
          class="bg-primary button q-ma-xs"
        /> 
      </div>  
      <!-- ! Seleccion SVG -->
      <div class="q-ma-md">
        <q-list bordered class="rounded-borders">
          <q-expansion-item
            expand-separator
            icon="perm_identity"
            label="Mesas restaurante"
            caption="Selecciona mesa"
            class="rounded-borders shadow-2"
            label-color="black"
            id="expansion-item"
            disable
            v-if="reservation.hour == ''"
          >
            
          </q-expansion-item>
          <q-expansion-item
            expand-separator
            icon="perm_identity"
            label="Mesas restaurante"
            caption="Selecciona mesa"
            class="rounded-borders shadow-2"
            label-color="black"
            id="expansion-item"
            v-else
          >
            <!-- ! Componente Formulario -->
            <SvgComponent svg-width="100%" svg-height="720" :hour="reservation.hour" :dia="reservation.date"></SvgComponent>
          </q-expansion-item>
          
        </q-list>
      </div>
    </div>
    <!-- ! Dialogo Horas -->
    <q-dialog v-model="alert">
      <div class="flex justify-center">
        <div
          style="width: 70%; border-radius: 4px"
          class="bg-primary flex justify-center q-pa-md">
            <q-btn
              v-for="hour in hours"
              :label="hour"
              @click="setHour(hour)"
              style="margin: 3.91px"
              class="bg-white"
              :key="hour"
              v-close-popup>
            </q-btn>
        </div>
      </div>
    </q-dialog>
  </q-form>
</template>
<script>
import moment from "moment/moment";
import SvgComponent from "src/components/SvgComponent.vue";
import {Notify} from 'quasar';

export default {
  name: 'ResFormComponent',
  components: {
    SvgComponent
  },
  data() {

    return {

      reservation: {
        email: "",
        fullname: "",
        tel: "",
        date: "",
        hour: "",
        table: ""
      },
      hours : ['10:00','11:00','12:00','13:00','14:00','15:00','16:00'],
      showMenu : true,
      alert : false,
    }
  },
  mounted() {
      let today = moment().format("L").split("/");

      this.reservation.date = today[2] + "/" + today[0] + "/" + today[1];
    },
  methods: {
    setHour(hour) {
      this.reservation.hour = hour;
    },
  } 
}
</script>
<style>

.flex-wrap{

  display: flex;
  flex-wrap: wrap;

}

.flex-item{

  flex: 1 0 10rem;

}

.calendar{

  flex: 1 1 15rem;

}

.hora{

  flex: 1 0 10rem;
}

.button{

  flex: 1 1 10rem;
}

</style>
