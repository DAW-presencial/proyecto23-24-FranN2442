<template>
  <q-page class="flex flex-center font-lato">
    <q-card style="width: 50%">
      <q-card-section v-if="mesa_seleccionada != ''">
        <p class="text-h6 bg-primary q-pa-sm rounded-borders shadow-2 text-white" >{{ $t('numTables') }} {{ this.mesa_seleccionada }}</p>
      </q-card-section>
      <q-card-section v-else>
        <p class="text-h6 bg-red q-pa-sm rounded-borders shadow-2 text-white">{{ $t('noTable') }}</p>
      </q-card-section>
      <q-card-section v-for="design in designs" :key="design.id" id="svg">
      </q-card-section>
      <q-separator />
      <q-card-actions>
        <q-btn class="text-subtitle2 text-white" flat v-for="design in designs" :key="design.id" @click="changeSelectedSvg(design.id)">{{
        design.attributes.hal_name }}</q-btn>
        <q-space />
        <q-btn class="text-subtitle2 bg-primary text-white" v-close-popup>{{ $t('btnSvg') }}</q-btn>
      </q-card-actions>
    </q-card>
  </q-page>
</template>
<script>
import { Notify, LocalStorage } from 'quasar'
import { apiUrl } from 'boot/axios'
import dinnerImage from '../assets/dinner.svg'
import dinnerImageGreen from '../assets/dinner-green.svg'
import dinnerImageRed from '../assets/dinner-red.svg'
import { useI18n } from 'vue-i18n';

export default {
  props: {
    svgWidth: String,
    svgHeight: String,
    hour: String,
    day: String,
    diners: String

  },
  setup() {
    const { t } = useI18n();
    return { t };
  },
  data() {

    return {

      designs: [],
      selected_hall: '1',
      tables: [],
      mesa_seleccionada: "",
      horaReserva: '',
      diaResera: '',
      comensalesReserva: '',
      reservas: []


    }

  },
  created() {

    console.log(this.hour, this.day);
    this.horaReserva = this.hour
    this.diaReserva = this.day
    this.comensalesReserva = this.diners

    this.getReservas()

    fetch(apiUrl + "/designs?filter[restaurant_id]=" + this.$route.query.id, {
      method: "GET",
      headers: {
        Accept: "application/vnd.api+json",
      },
    }).then((res) => res.json()).then((resultado) => {
      this.designs = resultado.data
      console.log(this.designs);
    });

  },
  updated() {

    console.log(this.hour, this.dia);
    this.horaReserva = this.hour
    this.diaResera = this.day
    this.comensalesReserva = this.diners

  },
  methods: {

    changeSelectedSvg(id) {

      this.selected_hall = id
      LocalStorage.set('dsid', id)
      this.changeSvg()
      // this.tables = this.designs.filter(design => design.attributtes.id.toLowerCase().includes(this.selected_hall))

    },


    changeSvg() {
      const scale = 0.5;
      let filtredDesign = this.designs.filter(design => design.id.toLowerCase().includes(this.selected_hall));
      console.log(filtredDesign);
      this.tables = filtredDesign[0].attributes.tables;
      console.log(this.tables);
      let card_section = document.getElementById('svg');
      card_section.innerHTML = "";

      let svg = document.createElementNS("http://www.w3.org/2000/svg", 'svg');

      svg.setAttribute('width', this.svgWidth);
      svg.setAttribute('height', this.svgHeight);

      let backgroundImage = document.createElementNS("http://www.w3.org/2000/svg", 'image');
      backgroundImage.setAttributeNS('http://www.w3.org/1999/xlink', 'href', dinnerImage);
      backgroundImage.setAttribute("x", "0");
      backgroundImage.setAttribute("y", "0");
      backgroundImage.setAttribute("width", this.svgWidth);
      backgroundImage.setAttribute("height", this.svgHeight);
      svg.appendChild(backgroundImage);

      let selectedTable = null;

      this.tables = JSON.parse(this.tables);
      for (let table in this.tables) {
        let tableData = this.tables[table];

        let image = document.createElementNS("http://www.w3.org/2000/svg", "image");

        image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', dinnerImage);
        image.setAttribute("x", tableData.x * scale);
        image.setAttribute("y", tableData.y * scale);
        image.setAttribute("width", tableData.w * scale + "px");
        image.setAttribute("height", tableData.h * scale + "px");

        if (tableData.ocupated_hours.includes(this.hour)) {
          image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', dinnerImageRed);
          image.addEventListener('click', () => {
            this.mesa_seleccionada = "";
            LocalStorage.remove('table');
            Notify.create({
              message: this.t('notifyT'),
              type: 'negative'
            });
          });
        } else {
          image.addEventListener('click', () => {
            if (selectedTable !== null) {
              selectedTable.setAttributeNS('http://www.w3.org/1999/xlink', 'href', dinnerImage);
            }
            image.setAttributeNS('http://www.w3.org/1999/xlink', 'href', dinnerImageGreen);
            selectedTable = image;
            this.mesa_seleccionada = tableData.number;
            LocalStorage.set('table', tableData.number);
          });
        }

        svg.appendChild(image);
      }

      card_section.appendChild(svg);
    }



    ,
    setTables() {

      let svg = document.getElementById("svg");


      for (let table in this.tables) {

        let table_attr = this.tables[table];

        console.log(table_attr);

        let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

        rect.setAttribute("x", table_attr.x)
        rect.setAttribute("y", table_attr.y)
        rect.setAttribute("width", table_attr.w)
        rect.setAttribute("heigth", table_attr.h)
        rect.setAttribute("fill", table_attr.color)

        svg.appendChild(rect);

      }


    },
    getReservas() {

      //   fetch("http://127.0.0.1:8000/api/v1/reservations?filter[restaurant_id]=" + this.$route.query.id + "&filter[date]=" + this.diaReserva, {
      //   method: "GET",
      //   headers: {
      //     Accept: "application/vnd.api+json",
      //   },
      // }).then((res) => res.json()).then((resultado) => {
      //     console.log(resultado);
      // });

    }

  }

};
</script>

<style scoped>

.mesa-seleccionada .mesa-text {
  background-color: #00796b;
  color: #ffffff;
  font-weight: bold;
}

.mesa-no-seleccionada .mesa-text {
  background-color: #b71c1c;
  color: #ffffff;
  font-weight: bold;
}

q-card-section {
  background-color: #f5f5f5;
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
}

q-card {
  margin: 20px;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.q-btn {
  margin: 1px;
  background-color: #00796b;
  color: white;
  font-weight: bold;
  border-radius: 5px;
  padding: 6px 16px;
}

.q-btn.flat {
  background-color: transparent;
  color: #00796b;
  font-weight: normal;
}

.q-separator {
  margin: 20px 0;
  border-color: #e0e0e0;
}
</style>
