<template>
  <q-page class="bg-grey-2">
    <div class="q-pa-md shadow-2 my_card bg-white">
      <q-card-section class="text-center">
        <div class="q-gutter-md" style="max-width: 300px">
          <div class="text-h5 text-weight-bold">Datos personales</div>
          <q-field outlined label="Name" stack-label>
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">{{ data.attributes.full_name }}</div>
            </template>
          </q-field>
          <q-field outlined label="Teléfono" stack-label>
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">{{ data.attributes.tel_num }}</div>
            </template>
          </q-field>
          <q-field outlined label="Email" stack-label>
            <template v-slot:control>
              <div class="self-center full-width no-outline" tabindex="0">{{ data.attributes.email }}</div>
            </template>
          </q-field>
          <div class="text-right q-gutter-md">
            <q-btn class="btn" label="Editar" color="green" />
          </div>
        </div>
      </q-card-section>
    </div>
  </q-page>
</template>


<script>
import { defineComponent } from 'vue'
import { LocalStorage } from 'quasar'

export default defineComponent({
  name: 'Home',
  data() {
    return {
      datos: null,
      data: {
        attributes: {
          full_name: '',
          password: '',
          tel_num: '',
        }
      },
    }
  },
  created() {
    this.loadData();
  },
  methods: {
    loadData() {
      const userData = LocalStorage.getItem("data");
      if (userData) {
        this.datos = userData;
        this.data.attributes.email = userData.email
        this.data.attributes.full_name = userData.full_name
        this.data.attributes.password = userData.password
        this.data.attributes.tel_num = userData.tel_num
      } else {
        this.redirectUnathorized();
      };
    },
    redirectUnathorized() {
      window.location.href = "/unathorized"
    }
  }
});
</script>
