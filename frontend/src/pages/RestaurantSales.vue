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
import { min } from 'moment';
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
            token  : '',
            restaurant_id : '',
            newSvgs : {}

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

    
        let local_arr = LocalStorage.getAll();
        this.restaurant_id = local_arr.restaurant_id;
        this.token = local_arr.token;
        console.log( "Restaurant Id: " + this.restaurant_id);

       fetch('http://booknow_api.randion.es/api/v1/designs?filter[restaurant_id]=' + this.restaurant_id,{
            headers: {
                'Accept' : 'application/vnd.api+json',
                'Authorization': `Bearer ${this.token}`
            }
       }).then((res) => res.json()).then(async (response) => {

            this.svgs = await response.data
            this.svg = this.svgs[0]
            this.setOptions()
            this.setSvg()
   
       })

    },
    created(){

        setInterval(()=> {

            this.timerSvg()
            clearInterval()

        },5000)

    

    },
    methods : {

        setOptions(){

            this.options = []

            for(let i = 0;i < this.svgs.length;i++){

                this.options.push(this.svgs[i].id)

            }

        },
        setSvg(){

            let date = new Date();
            let horas_actu = (date.getHours() < 10 ? '0' : '') + date.getHours()
            let mins_actu = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()

            let formated_h_actu = horas_actu + ':' + mins_actu
            
            let svgDiv = document.getElementById('svgDiv');
            
            svgDiv.innerHTML = ""

            let svg = document.createElementNS("http://www.w3.org/2000/svg",'svg');

            svg.setAttribute('width',Screen.width - 10)
            svg.setAttribute('height',Screen.height - 78)
            

            let tables  = JSON.parse(this.svg.attributes.tables)

            for(let table in tables){

                console.log('Table ' + table);
                console.log('----------------------------------------');

                let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

                rect.setAttribute("x",tables[table].x)
                rect.setAttribute("y",tables[table].y)
                rect.setAttribute("width",tables[table].w + "px")
                rect.setAttribute("height",tables[table].h + "px")
                let ocupated_h = tables[table].ocupated_hours
                if(ocupated_h.length == 0){
                    console.log('-------- No hay horas ocupadas ---------');
                    rect.setAttribute("fill","green")
                }else {
                    console.log(ocupated_h);
                    for(let i in ocupated_h){

                        rect.setAttribute("fill","green")
    
                        console.log('Hora reserva: ' + ocupated_h[i] + " Hora actual: " + formated_h_actu);
    
                        if(formated_h_actu == ocupated_h[i]){
    
                            rect.setAttribute("fill","red")
    
                        } 
                        // TODO: si la hora actual esta entre la ocupated_h[i] (hora acupada) o ocupated_h[i] -1 
                    }
                }

                // console.log(rect);

                svg.append(rect);
            }

            // console.log(svg);

            svgDiv.appendChild(svg)

        },
        logOut(){
            LocalStorage.remove('token')
            this.$router.push('employee-login')
        },
        timerSvg(){

            fetch('http://booknow_api.randion.es/api/v1/designs?filter[restaurant_id]=' + this.restaurant_id,{
                    headers: {
                        'Accept' : 'application/vnd.api+json',
                        'Authorization': `Bearer ${this.token}`
                    }
            }).then((res) => res.json()).then( async (response) => {

                    this.newSvgs = await response.data
                    console.log(this.newSvgs,this.svgs);
                    if(this.svgs.length !== this.newSvgs.length){

                        this.svgs = this.newSvgs
                        this.setOptions()
                    }
        
            })

        


        }

    }
}
</script>
<style scoped>

</style>