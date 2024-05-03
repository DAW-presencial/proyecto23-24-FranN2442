<template>
    <div class="q-pa-sm bg-grey max-heigth">

        <div id="svgDiv" >
            
        </div>
        <div class="flex justify-center">
            <q-select v-model="selected" :options="options" label="Pantalla" style="background-color: white;width: 200px;" class="q-mr-md rounded-borders" id="bar"/>
            <q-btn class="bg-red text-white" @click="logOut()">Cerrar sesión</q-btn>
        </div>
    </div>
</template>
<script>
import { api } from 'boot/axios'
import { LocalStorage,Screen } from 'quasar'
import SvgComponent from "src/components/SvgComponent.vue";

export default {

    name:'RestSalesPage',
    components : {

        SvgComponent

    },
    data() {

        return {

            restaurant : {},
            employee : {},
            svgs : {},
            selected : '1',
            options : [],
            svg : {},

        }

    },
    watch: {

        selected(newVal,oldVal){

            console.log('Valor nuevo: ' + newVal);
            this.svgs.forEach(element => {
                if(element.id == newVal){

                    this.svg = element
                    this.setSvg()

                }
            });

        }
    },
    beforeCreate(){

        console.log(Screen.height)
        let local_arr = LocalStorage.getAll();
        let rest_id = local_arr.restaurant_id;
        console.log(rest_id);
        let token = local_arr.token;

       fetch('http://booknow_api.randion.es/api/v1/designs?filter[restaurant_id]=' + rest_id,{
            headers: {
                'Accept' : 'application/vnd.api+json',
                'Authorization': `Bearer ${token}`
            }
       }).then((res) => res.json()).then(async (response) => {

            this.svgs = await response.data
            this.svg = this.svgs[0]
            this.setOptions()
            this.setSvg()
   
       })

    },
    methods : {

        setOptions(){

            for(let i = 0;i < this.svgs.length;i++){

                this.options.push(this.svgs[i].id)

            }

        },
        setSvg(){

            let svgDiv = document.getElementById('svgDiv');
            let bar = document.getElementById('bar')
            
            svgDiv.innerHTML = ""

            let svg = document.createElementNS("http://www.w3.org/2000/svg",'svg');

            svg.setAttribute('width',Screen.width)
            svg.setAttribute('height',Screen.height - 95)
            

            let tables  = JSON.parse(this.svg.attributes.tables)
            console.log(tables);

            for(let table in tables){

                let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

                rect.setAttribute("x",tables[table].x)
                rect.setAttribute("y",tables[table].y)
                rect.setAttribute("width",tables[table].w + "px")
                rect.setAttribute("height",tables[table].h + "px")
                rect.setAttribute("fill","green")

                console.log(rect);

                svg.append(rect);
            }

            console.log(svg);

            svgDiv.appendChild(svg)

        },
        logOut(){
            LocalStorage.remove('token')
            this.$router.push('employee-login')
        }

    }
}
</script>
<style scoped>

</style>