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
import { LocalStorage,Notify,Screen } from 'quasar'
import { isEqual } from 'lodash';
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
       }).then((res) => res.json()).then((response) => {

            this.svgs =  response.data
            console.log(this.svgs);
            // TODO: Comprobar que vienen svg
            this.svg = this.svgs[0]
            this.setOptions()
   
       })

       setInterval(()=> {

        this.timerSvg()


        },5000)

    },
    methods : {

        setOptions(){

            this.options = []

            for(let i = 0;i < this.svgs.length;i++){

                this.options.push(this.svgs[i].id)

            }

            let index = this.options.length

            this.svgs.forEach(element => {
                if(element.id == this.options[index - 1]){

                    this.svg = element
                    this.setSvg()

                }
            });


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

                // console.log('Table ' + table);
                // console.log('----------------------------------------');

                let rect = document.createElementNS("http://www.w3.org/2000/svg", "rect")

                rect.setAttribute("x",tables[table].x)
                rect.setAttribute("y",tables[table].y)
                rect.setAttribute("width",tables[table].w + "px")
                rect.setAttribute("height",tables[table].h + "px")
                let ocupated_h = tables[table].ocupated_hours
                if(ocupated_h.length == 0){
                    console.log('-------- No hay horas ocupadas ---------');
                    rect.setAttribute("fill","green")
                    rect.addEventListener('click',() => {

                        console.log('Mesa disponible');

                    })
                }else {
                    // console.log(ocupated_h);
                    for(let i in ocupated_h){

                        rect.setAttribute("fill","green")
                        rect.addEventListener('click',() => {

                            console.log('Mesa disponible');

                        })
    
                        console.log('Hora reserva: ' + ocupated_h[i] + " Hora actual: " + formated_h_actu);
                        let arr_format_hour2 = ocupated_h[i].split(':')
                        let hour_past = parseInt(arr_format_hour2) + 1
                        arr_format_hour2[0] = hour_past.toString()
                        
                        let formated_past = arr_format_hour2.join(':')// Pasada una h

    
                        if(formated_h_actu >= ocupated_h[i] && formated_h_actu <= formated_past){
    
                            rect.setAttribute("fill","red")
                            rect.addEventListener('click',() => {

                                Notify.create({

                                    message :"Mesa ocupada",
                                    type: 'negative'

                                })

                            })
                        } 
                        let arr_format_hour = ocupated_h[i].split(':')
                        let hour_prev = parseInt(arr_format_hour) - 1
                        arr_format_hour[0] = hour_prev.toString()
                        let formated_prev = arr_format_hour.join(':')
                        if(formated_h_actu < ocupated_h[i] && formated_h_actu > formated_prev ){

                            rect.setAttribute("fill","yellow")
                            rect.addEventListener('click',() => {

                               Notify.create({

                                    message :"Reserva a las " + ocupated_h[i] ,
                                    type: 'warning'

                               })

                            })

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
            }).then((res) => res.json()).then( (response) => {

                    // console.log(this.newSvgs,this.svgs);
                    if(this.svgs.length !== response.data.length){
                        this.svgs = response.data
                        this.setOptions()
                        return
                    } 

                    // Si hay el mismo numero de diseños:
                    for(let svg in this.svgs){

                        for(let resp_svg in response.data){

                            if(this.svgs[svg].id == response.data[resp_svg].id){

                                let svg_tables = JSON.parse(this.svgs[svg].attributes.tables)
                                let respose_tables = JSON.parse(response.data[resp_svg].attributes.tables)

                                let equals = isEqual(svg_tables,respose_tables)

                                if(!equals){
                                    console.log('Son diferentes',this.svgs[svg],response.data[resp_svg]);
                                    this.svgs[svg] = response.data[resp_svg] 
                                    console.log(this.svgs[svg].id,this.selected);
                                    if(parseInt(this.svgs[svg].id) == this.selected){
                                        this.svg = this.svgs[svg]
                                        this.setSvg()
                                    }

                                    this.$forceUpdate()
                                } else {


                                    let reservation_hours = JSON.parse(this.svgs[svg].attributes.tables)

                                    for(let key in reservation_hours){
                                        
                                        
                                        let hours = reservation_hours[key].ocupated_hours
                                        for(let hour of hours){

                                            let date = new Date();
                                            let horas_actu = (date.getHours() < 10 ? '0' : '') + date.getHours()
                                            let mins_actu = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()
                                            
                                            let formated_h_actu = horas_actu + ':' + mins_actu // Hora actual
    
                                            let arr_format_hour = hour.split(':')
                                            let hour_prev = parseInt(arr_format_hour) - 1
                                            arr_format_hour[0] = hour_prev.toString()
    
                                            let formated_prev = arr_format_hour.join(':')// Hora previa
    
                                            let arr_format_hour2 = hour.split(':')
                                            let hour_past = parseInt(arr_format_hour2) + 1
                                            arr_format_hour2[0] = hour_past.toString()
                                            
                                            let formated_past = arr_format_hour2.join(':')// Pasada una h

                                            if(formated_h_actu >= formated_prev && formated_h_actu <= formated_past){

                                                if(parseInt(this.svgs[svg].id) == this.selected){
                                    
                                                    this.setSvg()
                                                }
                                            }

                                        }

                                    }





                                }


                            }

                        }


                    }       
            })

        


        }
        

    }
}
</script>
<style scoped>

</style>