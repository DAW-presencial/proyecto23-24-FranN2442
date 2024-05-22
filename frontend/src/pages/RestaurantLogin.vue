<template>
  <div class="full-page">
      <div style="width: 100%;justify-content: center;">

        <div class="div-letras" style="font-size: 200px; margin-bottom: -60px;color:#1976d2 ;"><p>BOOK</p></div>
        <div class="div-letras" style="font-size: 120px; margin-left: 280px;"><p>NOW</p></div>
        <div class="div-letras" style="font-size: 70px; margin-left: 370px;;"><p>ERP</p></div>

      </div>
      <q-form class=" rounded-border q-pa-xl bg-primary">
        <div class="labels-div q-ma-xl">

            <q-input
            filled
              bg-color="white"
              label-color="primary"
              v-model="email"
              label="Email"
              class="q-ma-md"
              style="width: 300px;box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);"
            />

            <q-input
              filled
              bg-color="white"
              label-color="primary"
              v-model="password"
              label="Contraseña"
              class="q-ma-md"
              style="width: 300px;box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);"
              type="password"
            />

            <div class="q-pa-md">
              <q-btn label="Iniciar sesión" type="button" @click="login" color="blue-4" />
            </div>
        </div>
      </q-form>
  </div>
</template>
<script>
import { LocalStorage } from 'quasar'
import { Notify } from 'quasar'
import { api } from 'boot/axios'

export default {
  name : 'RestAccessPage',
  data() {

    return {

      email : '',
      password : ''

    }

  },
  methods : {

    login(){

      let params = {

        "email" : this.email,
        "password" : this.password,
        "device_name" : "my device"

      }

      fetch('http://booknow_api.randion.es/api/v1/restaurant_login',{
        method : 'POST',
        headers : {

          'Accept' : 'application/vnd.api+json',
          'Content-Type' : 'application/vnd.api+json',
        },
        body : JSON.stringify(params)

      }).then((res) => res.json()).then((resultado) => {

        if(! resultado.errors){

          LocalStorage.set('token_rest',resultado.token.split('|')[1])
          LocalStorage.set('restaurant_id',resultado.data.id)
          Notify.create({

            message: 'Inicio de sesión correcto!',
            type: 'positive'

          })
          setTimeout(() => {

            this.$router.push('employee-login')

          }, 3000)


        } else {

          Notify.create({

            message: resultado.errors[0].title,
            type: 'negative',

          })

        }
      })

    }

  }
};
</script>
<style scoped>
.full-page{

  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: right;
  align-items: center;


}
.rounded-border {
  height: 100vh;
  display: flex;
  align-items: center;
  background-color: white;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 255, 0.626), 0 8px 10px -6px rgba(4, 87, 230, 0.637);
}
p{
  margin: -42px 0px -20px;
}
.div-letras{

  display: flex; justify-content: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>
