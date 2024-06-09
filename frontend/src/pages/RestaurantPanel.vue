<template>
    <div class="bg-blue-2" style="height: 100vh">
        <div style="height: 10%;width: 100%;" class="flex items-center justify-between">
            <h3 class="q-pa-md text-primary rounded-borders" style="width: max-content;margin: 0%;">{{ this.restaurant.name }}</h3>
            <q-btn class="bg-red text-white q-mr-md" style="height: max-content;" @click="logOut()">Cerrar sesión</q-btn>
        </div>
        <div class="bg-blue-2" style="height: 90%;width: 100%;display: flex;">
            <div class="q-ma-md rounded-borders " style="height: 100%;width: 50%;">
                <div class="q-pa-md" style="height: 10%;">
                    <h4 class="q-ma-md ">Configuraciones</h4>
                </div>
                <div class="flex justify-between q-pa-md rounded-borders" style="height: 90%">
                    <div class="flex " style="height: 100%;width: 50%;">
                        <q-btn label="Empleados" color="primary" stack icon="person" class="q-ma-md shadow-2" @click="this.getEmployees()" style="height: max-content;"></q-btn>
                        <q-btn label="Punto de venta" color="primary" stack icon="table_restaurant" class="q-ma-md shadow-2" @click="goToDesigns()" style="height: max-content"></q-btn>
                    </div>
                </div>
            </div>
            <div class="bg-blue-2" style="height: 100%;width: 50%;">
                <h4>Información Restaurante</h4>
                <q-form>
                    <q-input
                        filled
                        bg-color="white"
                        label-color="primary"
                        v-model="this.restaurantEdit.name"
                        label="Nombre Restaurante"
                        class="q-ma-md"
                        :rules="[val => !!val || 'Obligatorio']"
                            />
                            <q-input
                        filled
                        bg-color="white"
                        label-color="primary"
                        v-model="this.restaurantEdit.description"
                        label="Descripción"
                        class="q-ma-md"
                        :rules="[val => !!val || 'Obligatorio']"
                            />
                            <div class="flex">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.tel_num"
                            label="Numero de Teléfono"
                            class="q-ma-md"
                            style="width: 46%;"
                            :rules="[val => val.length == 9 || 'El numero de teléfono debe ser 9 digitos.']"
                                />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.email"
                            label="Email"
                            class="q-ma-md"
                            style="width: 46%;"
                            :rules="[val => !!val || 'Obligatorio']"
                                />
                    </div>
                    <q-input
                        filled
                        bg-color="white"
                        label-color="primary"
                        v-model="this.restaurantEdit.address"
                        label="Dirección"
                        class="q-ma-md"
                        :rules="[val => !!val || 'Obligatorio']"/>
                    <div class="flex">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.location"
                            label="Localidad"
                            class="q-ma-md"
                            style="width: 46%;"
                            :rules="[val => !!val || 'Obligatorio']"
                                />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.postal_code"
                            label="Código Postal"
                            class="q-ma-md"
                            style="width: 46%;"
                            :rules="[val => val.length == 5 || 'Código postal de 5 digitos']"
                                />
                    </div>
                    <div class="flex">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.category"
                            label="Categoria"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"/>
                            <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.restaurantEdit.capacity"
                            label="Capacidad Comensales"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"/>

                    </div>
                    <div class="flex justify-between">
                        <q-btn class="q-ma-md" color="orange" @click="this.dlTourns = true">Modificar Horario</q-btn>
                        <q-btn class="q-ma-md" @click="updateRestaurant()" color="green">confirmar</q-btn>
                    </div>
                </q-form>
            </div>
        </div>

        <!-- * Dialogo de selección de Empleado -->
        <q-dialog v-model="icon" class="bg-info">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Employees</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section v-for="employee in this.employees" :key="employee.id" >
                    <div>
                        <q-btn color="primary" class="" @click="this.editEmployee(employee)">{{ employee.attributes.full_name }}</q-btn>
                        <q-btn color="red" class="q-ml-md" icon="delete" @click="this.deleteEmployee(employee.id)"></q-btn>
                    </div>
                </q-card-section>
                <q-card-section class="row items-center q-pb-none">
                    <q-btn icon="add" color="green" label="Nuevo empleado" class="q-mb-md" @click="this.creteEmployee()"/>
                </q-card-section>
            </q-card>
        </q-dialog>
        <!-- * Dialogo de edición de Empleado -->
        <q-dialog v-model="icon2" persistent transition-show="scale" transition-hide="scale">
            <q-card class="bg-teal text-white" style="width: 300px">
                <q-card-section class="flex justify-between">
                    <div class="text-h6">Editando Empleado</div>
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <q-form id="emplo-dl">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.name"
                            label="Nombre"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.email"
                            label="Email"
                            class="q-ma-md"
                            type="email"
                            :rules="[val => !!val || 'Obligatorio']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.role"
                            label="Rol"
                            class="q-ma-md"
                            :rules="[val => val in ['employee','admin'] || 'El rol debe ser (empleado) o (admin)']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.tel_num"
                            label="Numero de teléfono"
                            class="q-ma-md"
                            :rules="[   val => val.length == 9  || 'El numero de telefono debe contener 9 digitos.']"

                            />
                    </q-form>
                </q-card-section>

                <q-card-actions align="right" class="bg-white text-teal">
                <q-btn flat label="Editar" @click="this.putEmployee()"  />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <!-- * Dialogo de creación de Empleado -->
        <q-dialog v-model="dlPass" persistent transition-show="scale" transition-hide="scale">
            <q-card class="bg-white" style="width: 300px">
                <q-card-section class="flex justify-between bg-info text-white">
                    <div class="text-h6">Nuevo empleado</div>
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <q-form id="emplo-dl">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.name"
                            label="Nombre"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.email"
                            label="Email"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.role"
                            label="Rol"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Obligatorio']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.tel_num"
                            label="Numero de teléfono"
                            class="q-ma-md"
                            :rules="[   val => val.length == 9  || 'El numero de telefono debe contener 9 digitos.']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.pass"
                            label="Pin de Acceso"
                            class="q-ma-md"
                            :rules="[ val => val.length == 4 || 'El numero pin debe tener 4 numeros.']"
                            />
                    </q-form>
                </q-card-section>

                <q-card-actions align="right" class="bg-info text-white">
                <q-btn flat label="Crear" v-close-popup @click="storeEmployee()"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <!-- * Diálogo de edicion de los turnos -->
        <q-dialog v-model="dlTourns" class="bg-primary">
            <q-card>
                <q-card-section class="row items-center bg-blue-2">
                    <div class="text-h6">Horarios</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section v-for="( tourn, key) in this.restaurantEdit.tourns" :key="key" class="bg-blue-1">
                    <span class="text-primary q-ma-md text-h6">{{ tourn.tourn_name }}</span>
                    <div class="flex">
                        <q-input
                        filled
                        bg-color="white"
                        label-color="black"
                        v-model="this.restaurantEdit.tourns[key].start"
                        label="Inicio"
                        class="q-ma-md"
                        :rules="[val => !!val || 'Obligatorio']"/>
                    <q-input
                        filled
                        bg-color="white"
                        label-color="black"
                        v-model="this.restaurantEdit.tourns[key].end"
                        label="Fin"
                        class="q-ma-md"
                        :rules="[val => !!val || 'Obligatorio']"/>
                    </div>
                </q-card-section>
                <q-card-section class="row items-center q-pb-none bg-blue-2">
                    <q-btn color="green" label="Guardar horario" class="q-mb-md" @click="this.dlTourns = false"/>
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>
<script>

import { LocalStorage } from "quasar";
import { Notify } from "quasar";
import { apiUrl } from 'boot/axios'

export default {
    name: 'Restaurant Panel',
    data() {

        return {
            rest_id : '',
            token_rest : '',
            restaurant: {},
            employees: {},
            icon : false,
            icon2 : false,
            employeeEdit : {

                name : "",
                email : "",
                role : "",
                tel_num : "",
                id: ""

            },
            employeeCreate : {

                name : "",
                email : "",
                role : "",
                tel_num : "",
                pass : ""

            },
            restaurantEdit : {

                name : "",
                address: "",
                location: "",
                postal_code : "",
                tel_num : "",
                category : "",
                email: "",
                tourns : {},
                capacity : "",
                description : ""


            },
            dlPass : false,
            dlTourns : false

        }

    },
    created() {

        let local_arr = LocalStorage.getAll();
        this.rest_id = local_arr.restaurant_id;
        this.token_rest = local_arr.token_rest;
        this.token = local_arr.token

        fetch(apiUrl + '/restaurants/' + this.rest_id, {
            headers: {
                'Accept': 'application/vnd.api+json',
                'Authorization': `Bearer ${this.token_rest}`
            }
        }).then((res) => res.json()).then((response) => {

            this.restaurant = response.data.attributes

            console.log(this.restaurant);

            this.restaurantEdit.address = this.restaurant.address
            this.restaurantEdit.category = this.restaurant.category
            this.restaurantEdit.name = this.restaurant.name
            this.restaurantEdit.tel_num = this.restaurant.tel_num
            this.restaurantEdit.capacity = this.restaurant.capacity
            this.restaurantEdit.email = this.restaurant.email
            this.restaurantEdit.location = this.restaurant.location
            this.restaurantEdit.postal_code = this.restaurant.postal_code
            this.restaurantEdit.description = this.restaurant.description
            this.restaurantEdit.tourns = JSON.parse(this.restaurant.tourns)



        })

    },
    methods: {
        getEmployees(){

            fetch(apiUrl + '/employees?filter[restaurant_id]=' + this.rest_id,{
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Authorization': `Bearer ${this.token_rest}`
                }
            }).then((res) => res.json()).then((response) => {

                this.employees = response.data
                console.log(this.employees);
                this.icon = true

            })

        },
        deleteEmployee(employee){

            let notification = Notify.create({

                message: "Estas seguro de eliminar este usuario?",
                type : "warning",
                timeout: 0,
                actions: [{ label: 'Confirmar', color: 'white', handler: () => {

                            fetch(apiUrl + '/employees/' + employee,{
                        headers: {
                            'Accept': 'application/vnd.api+json',
                            'Authorization': `Bearer ${this.token}`
                        },
                        method:"DELETE"
                        }).then((res) => {

                            console.log(res);
                            if(res.status == 204){

                                Notify.create({

                                    message: "Empleado eliminado",
                                    type : 'positive'

                                })

                            }

                        }).catch((error) => {

                            Notify.create({

                                message: error,
                                type : 'negative'

                            })
                        })

                } },{
            icon: 'cancel',
            color: 'white',
            handler: () => {
              notification(); // This will close the notification
            }}
            ]

            })

        },
        editEmployee(employee){

            this.icon2 = true

            this.employeeEdit.email = employee.attributes.email
            this.employeeEdit.role = employee.attributes.role
            this.employeeEdit.name = employee.attributes.full_name
            this.employeeEdit.tel_num = employee.attributes.tel_num
            this.employeeEdit.id = employee.id

            console.log(employee);

        },
        putEmployee(){

            console.log(this.employeeEdit);

            fetch(apiUrl + '/employees/' + this.employeeEdit.id,{
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type' : 'application/vnd.api+json',
                    'Authorization': `Bearer ${this.token}`
                },
                method: 'PATCH',
                body: JSON.stringify({data:{ attributes : {

                    full_name : this.employeeEdit.name,
                    email : this.employeeEdit.email,
                    role : this.employeeEdit.role,
                    tel_num : this.employeeEdit.tel_num,

                }}})
            }).then((res) => res.json()).then((response) => {


                Notify.create({

                    message: "Empleado editado",
                    type : "positive"

                })

                console.log(response);

            }).catch((error) => {

                Notify.create({

                    message: error,
                    type : "negative"

                })

            })

        },
        creteEmployee(){

            this.dlPass = true

        },
        storeEmployee(){

            console.log(this.employeeCreate);

            fetch(apiUrl + '/employees',{
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type' : 'application/vnd.api+json',
                    'Authorization': `Bearer ${this.token}`
                },
                method: 'POST',
                body: JSON.stringify({data:{ attributes : {

                    full_name : this.employeeCreate.name,
                    email : this.employeeCreate.email,
                    role : this.employeeCreate.role,
                    tel_num : this.employeeCreate.tel_num,
                    pin : this.employeeCreate.pass,
                    restaurant_id : this.rest_id

                }}})
            }).then((res) => res.json()).then((response) => {


                Notify.create({

                    message: "Nuevo empleado creado",
                    type : "positive"

                })

                this.employeeCreate = {

                    name : "",
                    email : "",
                    role : "",
                    tel_num : "",
                    pass : ""
                }

            }).catch((error) => {

                Notify.create({

                    message: error,
                    type : "negative"

                })

            })

        },
        goToDesigns(){

            this.$router.push('restaurant-sales')

        },
        editTourns(){



        },
        logOut(){

            LocalStorage.remove('token')
            LocalStorage.remove('usr')
            clearInterval(this.stopInterval)
            // console.log('LogOut:' + this.stopInterval);
            this.$router.push('employee-login')
        },
        updateRestaurant(){

            console.log(this.restaurantEdit);

            fetch(apiUrl + '/restaurants/' + this.rest_id,{
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type' : 'application/vnd.api+json',
                    'Authorization': `Bearer ${this.token}`
                },
                method: 'PATCH',
                body: JSON.stringify({data:{ attributes : {

                    name : this.restaurantEdit.name,
                    email : this.restaurantEdit.email,
                    address : this.restaurantEdit.address,
                    tel_num : this.restaurantEdit.tel_num,
                    location : this.restaurantEdit.location,
                    postal_code : this.restaurantEdit.postal_code,
                    category : this.restaurantEdit.category,
                    capacity : this.restaurantEdit.capacity,
                    description : this.restaurantEdit.description,
                    tourns : JSON.stringify(this.restaurantEdit.tourns)


                }}})
            }).then((res) => res.json()).then((response) => {


                Notify.create({

                    message: "Parametros actualizados",
                    type : "positive"

                })

            })

        }
    }
}
</script>
