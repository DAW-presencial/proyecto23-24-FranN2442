<template>
  <div class="container">
    <div class="rounded-div">
      <div class="search-div">
        <q-input type="search" class="search-input" rounded outlined v-model="searchRestaurant" @input="searchByInput"
          placeholder="Buscar restaurante..." />
        &nbsp;
        <GeolocationComponent></GeolocationComponent>
      </div>
    </div>
  </div>
  <div class="card-container">
    <div class="card-div">
      <div v-if="restaurantsFilter.length === 0">
        No se encontraron resultados.
      </div>
      <q-card class="my-card" v-for="rest in restaurantsFilter" :key="rest.id">
        <q-img src="https://cdn.quasar.dev/img/parallax2.jpg">
          <div class="absolute-bottom text-h6">
            <q-btn :to="'/restaurant?id=' + rest.id">{{ rest.attributes.name }}</q-btn>
          </div>
        </q-img>
        <q-card-section>
          <div>
            <i class="fa-solid fa-location-dot restaurant-icon"></i>{{ rest.attributes.address }}
          </div>
          <div>
            <i class="fa-solid fa-location-crosshairs restaurant-icon"></i>{{ rest.attributes.location }}
          </div>
          <div>
            <i class="fa fa-map-pin restaurant-icon" aria-hidden="true"></i>{{ rest.attributes.postal_code }}
          </div>
          <div>
            <i class="fa-solid fa-phone restaurant-icon"></i>{{ rest.attributes.tel_num }}
          </div>
          <div>
            <i class="fa-solid fa-envelope restaurant-icon"></i>{{ rest.attributes.email }}
          </div>
          <div>
            <i class="fa-solid fa-clock restaurant-icon"></i>{{ rest.attributes.opening_hours }}
          </div>
          <div>
            <i class="fa-solid fa-person restaurant-icon"></i>{{ rest.attributes.capacity }}
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
  <FooterComponent></FooterComponent>
</template>

<script>
import { defineComponent, ref } from 'vue'
import FooterComponent from '../components/FooterComponent.vue'
import GeolocationComponent from '../components/Geolocation.vue'
export default defineComponent({
  name: 'Home',
  components: {
    FooterComponent,
    GeolocationComponent
  },

  data() {
    return {
      searchRestaurant: '', // Variable para almacenar el término de búsqueda
      restaurants: [],
      restaurantsFilter: [],
    };
  },

  async created() {
    await this.fetchRestaurants();
    //console.log('restaurants ' + this.restaurants.length);
  },
  /*
  La propiedad computed en Vue se utiliza para definir
  propiedades calculadas que se actualizan automáticamente
  cuando alguna de las dependencias cambia.
  */
  computed: {
    searchByInput() {
      if (this.searchRestaurant) {
        this.restaurantsFilter = this.restaurants.filter(rest => rest.attributes.name.toLowerCase().includes(this.searchRestaurant.toLocaleLowerCase()));
      } else {
        this.restaurantsFilter = this.restaurants
      }
    }
  },

  methods: {
    async fetchRestaurants() {
      const response = await fetch('http://127.0.0.1:8000/api/v1/restaurants', {
        method: 'GET',
        headers: {
          'accept': 'application/vnd.api+json'
        }
      });
      if (!response.ok) {
        throw new Error('Failed to fetch restaurants');
      }
      const resultado = await response.json();
      this.restaurants = resultado.data;
      //console.log('restaurants:', this.restaurants[0].attributes.name);
    }
  },
});
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.rounded-div {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 90%;
  height: 40vh;
  margin-top: 20px;
  border-radius: 10px;
  background-color: #1480ed;
  padding: 20px;
}

.search-div {
  display: inline-flex;
}

.search-input {
  width: 50vw;
  border-radius: 30px;
  background-color: white;
}

.search-btn {
  color: white;
  background-color: black;
  border-radius: 30px;
}

.card-container {
  margin: 2%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.card-div {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  width: 90%;
}

.my-card {
  width: 100%;
  max-width: 250px;
  margin: 0.5%;
}

.card-div-title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  width: 90%;
  display: flex;
  justify-content: flex-end;
}

.no-underline {
  text-decoration: none;
  margin: 1%;
}

.restaurant-icon {
  margin-right: 2%;
  padding-right: 2%;
}
</style>
