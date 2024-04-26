<template>
  <q-card class="mulish">
    <q-card-section v-if="mesa_seleccionada != ''">
      <p class="bg-primary q-pa-sm mulish rounded-borders shadow-2 text-white" style="margin:0%;width: auto">Numero de mesa: {{ this.mesa_seleccionada }}</p>
    </q-card-section>
    <q-card-section v-else >
      <p class="bg-red q-pa-sm mulish rounded-borders shadow-2 text-white" style="margin:0%;width: auto">No hay mesa seleccionada</p>
    </q-card-section>
    <q-card-section v-for="design in designs" :key="design.id" id="svg">

    </q-card-section>
    <q-separator />
    <q-card-actions>
      <q-btn flat v-for="design in designs" :key="design.id" @click="changeSelectedSvg(design.id)">{{design.attributes.hal_name}}</q-btn>
      <q-space/>
      <q-btn class="bg-primary text-white" v-close-popup>close</q-btn>
    </q-card-actions>
  </q-card>

</template>
<script>
import {Notify,LocalStorage} from 'quasar';

export default {
  props : {
    svgWidth :String,
    svgHeight : String,
    hour: String,
    day: String,
    diners : String

  },
  data(){

    return {

      designs : [],
      selected_hall : '1',
      tables : [],
      mesa_seleccionada : "",
      horaReserva : '',
      diaResera : '',
      comensalesReserva : '',
      reservas: []


    }

  },
  created() {

    console.log(this.hour,this.dia);
    this.horaReserva = this.hour
    this.diaReserva = this.dia
    this.comensalesReserva = this.diners

    this.getReservas()

    fetch("http://booknow.randion.es/api/v1/designs?filter[restaurant_id]=" + this.$route.query.id, {
      method: "GET",
      headers: {
        Accept: "application/vnd.api+json",
      },
    }).then((res) => res.json()).then((resultado) => {
        this.designs = resultado.data
        console.log(this.designs);
    });

  },
  updated(){

    console.log(this.hour,this.dia);
    this.horaReserva = this.hour
    this.diaResera = this.dia
    this.comensalesReserva = this.diners

  },
  methods : {

    changeSelectedSvg(id){

      this.selected_hall = id
      this.changeSvg()
      // this.tables = this.designs.filter(design => design.attributtes.id.toLowerCase().includes(this.selected_hall))

    },
    changeSvg(){

      let filtredDesign = this.designs.filter(design => design.id.toLowerCase().includes(this.selected_hall))
      console.log(filtredDesign);
      this.tables = filtredDesign[0].attributes.tables
      console.log(this.tables);
      let card_section = document.getElementById('svg')
      card_section.innerHTML = "";

      let svg = document.createElementNS("http://www.w3.org/2000/svg",'svg');

      svg.setAttribute('width',this.svgWidth)
      svg.setAttribute('height',this.svgHeight)

      for(let table in this.tables){

        let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

        rect.setAttribute("x",this.tables[table].x)
        rect.setAttribute("y",this.tables[table].y)
        rect.setAttribute("width",this.tables[table].w + "px")
        rect.setAttribute("height",this.tables[table].h + "px")
        if (!this.tables[table].state){
          rect.setAttribute("fill","red")
          rect.addEventListener('click', () => {

            Notify.create({

              message : 'La mesa esta ocupada!',
              type : 'negative'
            })

          })
        } else {
          rect.setAttribute("fill","green")
          rect.addEventListener('click', () => {

            this.mesa_seleccionada = this.tables[table].numero
            LocalStorage.set('table',this.tables[table].numero)

          })
        }

        console.log(rect);
        svg.append(rect);

      }

      card_section.append(svg)

    },
    setTables(){

      let svg = document.getElementById("svg");


      for(let table in this.tables){

        let table_attr = this.tables[table];

        console.log(table_attr);

        let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

        rect.setAttribute("x",table_attr.x)
        rect.setAttribute("y",table_attr.y)
        rect.setAttribute("width",table_attr.w)
        rect.setAttribute("heigth",table_attr.h)
        rect.setAttribute("fill",table_attr.color)

        svg.appendChild(rect);

      }


    },
    getReservas(){

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
q-card-section{

  background-color: whitesmoke;

}
</style>
