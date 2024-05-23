<template>
  <div class="container">
    <div class="rounded-div">
      <div class="title-container">
        <div class="title-text my-font2">{{ $t('titleHome') }}</div>
      </div>
      <div class="search-div my-font2">
        <q-input type="search" class="search-input" rounded outlined v-model="searchRestaurant" @input="searchByInput"
          :placeholder="$t('searchBar')" />&nbsp;
        <a @click="clearSearch" style="cursor: pointer;">
          <i class="fa-regular fa-circle-xmark"></i>
        </a>
        &nbsp;
        <GeolocationComponent></GeolocationComponent>
      </div>
    </div>
  </div>
  <div class="card-container">
    <h5 class="text-center text-h5 my-font" style="font-weight: bold; text-transform: uppercase;">{{ $t('restaurant') }}</h5>
    <div class="card-div">
      <div class="my-font2" v-if="restaurantsFilter.length === 0">
        {{ $t('noResults') }}
      </div>
      <q-card class="my-card" v-for="rest in restaurantsFilter" :key="rest.id" flat bordered>
        <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" />
        <q-card-section>
          <div class="row no-wrap items-center">
            <div class="col text-h6 ellipsis">
              {{ rest.attributes.name }}
            </div>
          </div>
        </q-card-section>
        <q-card-section class="q-pt-none">
          <div class="text-overline text-orange-9">{{ rest.attributes.category }}</div>
          <div class="text-caption text-grey">
            Small plates, salads & sandwiches in an intimate setting.
          </div>
        </q-card-section>
        <q-separator />
        <q-card-actions class="container">
          <q-btn flat color="primary">
            <router-link :to="modificarRuta(rest.id)" class="text-primary text-weight-bold cursor-pointer"
              style="text-decoration: none">{{ $t('book') }}</router-link>
          </q-btn>
        </q-card-actions>
      </q-card>
    </div>
    <h5 class="text-center text-h5 my-font" style="font-weight: bold; text-transform: uppercase;">{{ $t('offer') }}</h5>
    <div class="q-pa-md" style="width: 90%;">
      <div class="q-gutter-md">
        <q-carousel v-model="slide" transition-prev="scale" transition-next="scale" swipeable animated
          control-color="white" navigation padding arrows height="300px"
          class="bg-primary text-white shadow-1 rounded-borders" infinite autoplay>
          <q-carousel-slide name="layers" class="column no-wrap flex-center" img-src="../assets/book-now-users.png">
            <q-icon name="discount" class="disc" size="56px" />
            <div class="q-mt-md text-center">
              <h6 class="text-h6 my-font2 bg-orange">{{ $t('offer1') }}</h6>
            </div>
          </q-carousel-slide>
          <q-carousel-slide name="style" class="column no-wrap flex-center" img-src="../assets/book-now-descuentos.jpg">
            <q-icon name="style" size="56px" />
            <div class="q-mt-md text-center">
              <h6 class="text-h6 my-font2 bg-orange">{{ $t('offer2') }}</h6>
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
    <h5 class="text-center text-h5 text-bold text-uppercase my-font">{{ $t('registerRest') }}</h5>
    <div class="card-div">
      <q-card class="restRegister">
        <q-card-section horizontal>
          <q-img class="col-5" src="../assets/unete-a-nosotros.jpg" />
          <q-card-section class="bg-grey-4">
            <div class="col text-h6 text-caption text-center text-bold my-font2" style="font-size: 22px; padding-top: 2%;">
              {{ $t('joinUs') }}
            </div>
            <h6 class="left text-black-9 text-caption my-font2" style="font-size: 18px;">{{ $t('joinUsDescription') }}</h6>
            <div class="text-center">
              <q-btn class="custom bg-grey-4 my-font2" to="/send-mail" :label="$t('joinUsBtn')" />
            </div>
          </q-card-section>
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
    GeolocationComponent,
  },

  data() {
    return {
      searchRestaurant: '',
      restaurants: [],
      restaurantsFilter: [],
      slide: ref('style'),
    };
  },

  async created() {
    await this.fetchRestaurants();
    this.searchByCode();
  },
  computed: {
    searchByInput() {
      if (this.searchRestaurant) {
        this.restaurantsFilter = this.restaurants.filter(rest => rest.attributes.name.toLowerCase().includes(this.searchRestaurant.toLocaleLowerCase())
          || rest.attributes.category.toLowerCase().includes(this.searchRestaurant.toLocaleLowerCase()));
      } else {
        this.restaurantsFilter = this.restaurants
      }
    },
  },

  methods: {
    async fetchRestaurants() {
      const response = await fetch('http://booknow_api.randion.es/api/v1/restaurants', {
        method: 'GET',
        mode: 'no-cors',
        headers: {
          'accept': 'application/vnd.api+json'
        }
      });
      if (!response.ok) {
        throw new Error('Failed to fetch restaurants');
      }
      const resultado = await response.json();
      this.restaurants = resultado.data;
    },
    clearSearch() {
      this.searchRestaurant = '';
      this.restaurantsFilter = this.restaurants
    },
    searchByCode() {
      const zip_code = localStorage.getItem("zip_code");
      const zip_code_1 = localStorage.getItem("zip_code_1");
      const zip_code_2 = localStorage.getItem("zip_code_2");
      if (zip_code && zip_code_1 && zip_code_2) {
        this.restaurantsFilter = this.restaurants.filter(rest => {
          const zip = parseInt(rest.attributes.postal_code);
          return zip === parseInt(zip_code) || zip === parseInt(zip_code_1) || zip === parseInt(zip_code_2);
        });
      } else {
        this.restaurantsFilter = this.restaurants
      }
    },
    modificarRuta(restId) {
      function generarCadenaAleatoria(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
      }
      const nuevoParametro = generarCadenaAleatoria(5);
      const nuevoValor = generarCadenaAleatoria(8);
      return `/restaurant?id=${restId}&${nuevoParametro}${nuevoValor}`;
    },
  },
});
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.rounded-div {
  display: flex;
  flex-direction: column;
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
  margin-top: 4vh;

}

.search-input {
  width: 30vw;
  border-radius: 30px;
  background-color: white;
}

.search-btn {
  color: white;
  background-color: black;
  border-radius: 30px;
}

.card-container {
  display: flex;
  flex-direction: column;
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

.title-container {
  text-align: center;
}

.title-text {
  font-size: 44px;
  font-weight: bold;
  color: #FFFFFF;
}

.restRegister {
  width: 100%;
  max-width: 84vw;
  padding: 0%
}

.custom {
  border: 1px solid #1480ed;
  background-color: white;
  color: #1480ed;
}

@media screen and (max-width: 375px) and (max-height: 667px) {
  .container {
    padding: 10px;
  }

  .rounded-div {
    border-radius: 10px;
  }

  .title-text {
    font-size: 30px;
  }

  .search-input {
    width: 80%;
  }

  .disc{
    margin-top: 25px;
  }
}
</style>
