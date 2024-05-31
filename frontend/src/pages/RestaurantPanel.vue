<template>
    <div class="bg-info q-pa-md" style="height: 100vh">
        <h3 class="q-ma-md bg-white q-pa-md rounded-borders" style="width: max-content;height: 10%">
            {{ this.restaurant.name }}</h3>
        <div class="bg-white q-ma-md rounded-borders " style="height: 80%">
            <div class="q-pa-md">
                <h4 class="q-ma-md ">Configurations</h4>
            </div>
            <div class="flex">
                <q-btn label="Employees" color="green" stack icon="person" class="q-ma-md" @click="this.getEmployees()"></q-btn>
                <q-btn label="Designs" color="purple" stack icon="table_restaurant" class="q-ma-md" @click="goToDesigns()"></q-btn>
                <q-btn label="Restaurant" color="primary" stack icon="settings" class="q-ma-md"></q-btn>
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
                    <q-btn icon="add" color="green" label="New employee" class="q-mb-md" @click="this.creteEmployee()"/>
                </q-card-section>
            </q-card>
        </q-dialog>
        <!-- * Dialogo de edición de Empleado -->
        <q-dialog v-model="icon2" persistent transition-show="scale" transition-hide="scale">
            <q-card class="bg-teal text-white" style="width: 300px">
                <q-card-section class="flex justify-between">
                    <div class="text-h6">Editing Employee</div>
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <q-form id="emplo-dl">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.name"
                            label="Name"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Required']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.email"
                            label="Email"
                            class="q-ma-md"
                            type="email"
                            :rules="[val => !!val || 'Required']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.role"
                            label="Role"
                            class="q-ma-md"
                            :rules="[val => val in ['employee','admin'] || 'Role needs to be admin or employee']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeEdit.tel_num"
                            label="Phone Number"
                            class="q-ma-md"
                            :rules="[   val => val.length == 9  || 'Phone number needs to be 9 numbers']"
                            
                            />
                    </q-form>
                </q-card-section>

                <q-card-actions align="right" class="bg-white text-teal">
                <q-btn flat label="Edit" @click="this.putEmployee()"  />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <!-- * Dialogo de creación de Empleado -->
        <q-dialog v-model="dlPass" persistent transition-show="scale" transition-hide="scale">
            <q-card class="bg-white" style="width: 300px">
                <q-card-section class="flex justify-between bg-info text-white">
                    <div class="text-h6">New Employee</div>
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <q-form id="emplo-dl">
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.name"
                            label="Name"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Required']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.email"
                            label="Email"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Required']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.role"
                            label="Role"
                            class="q-ma-md"
                            :rules="[val => !!val || 'Required']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.tel_num"
                            label="Phone Number"
                            class="q-ma-md"
                            :rules="[   val => val.length == 9  || 'Phone number needs to be 9 numbers']"
                            />
                        <q-input
                            filled
                            bg-color="white"
                            label-color="primary"
                            v-model="this.employeeCreate.pass"
                            label="Password"
                            class="q-ma-md"
                            :rules="[ val => val.length == 4 || 'Please password needs to be 4 numbers ']"
                            />
                    </q-form>
                </q-card-section>

                <q-card-actions align="right" class="bg-info text-white">
                <q-btn flat label="Create" v-close-popup @click="storeEmployee()"/>
                </q-card-actions>
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
            dlPass : false

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

        }
    }
}
</script>
