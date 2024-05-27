<template>
  <q-btn class="search-btn" @click="getCurrentPosition">{{ $t('search') }}</q-btn>
</template>

<script>
import { ref, onBeforeUnmount } from 'vue'
import { Geolocation } from '@capacitor/geolocation'

export default {
  setup() {
    const position = ref('Determinando...')
    const codigoPostal = ref(null)
    let geoId

    async function obtenerCodigoPostal(latitud, longitud) {
      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitud}&lon=${longitud}&format=json`);
        const data = await response.json();
        codigoPostal.value = data.address.postcode;

        const codigoPostalSumado = parseInt(codigoPostal.value) + 1;
        const codigoPostalActual = parseInt(codigoPostal.value);
        const codigoPostalRestado = parseInt(codigoPostal.value) - 1;
        localStorage.setItem("zip_code", codigoPostalSumado);
        localStorage.setItem("zip_code_1", codigoPostalActual);
        localStorage.setItem("zip_code_2", codigoPostalRestado);
        window.location.reload();

      } catch (error) {
        console.error('Error obteniendo el código postal actual:', error);
        codigoPostal.value = 'No disponible';
      }
    }

    function getCurrentPosition() {
      Geolocation.getCurrentPosition()
        .then(newPosition => {
          console.log('Current', newPosition)
          position.value = newPosition
          obtenerCodigoPostal(newPosition.coords.latitude, newPosition.coords.longitude)
        })
        .catch(error => {
          if (error.code === 1) {
            console.error('El usuario ha denegado la geolocalización.')
            position.value = 'Permiso denegado'
          } else {
            console.error('Error obteniendo la posición actual:', error)
            position.value = 'Error'
          }
        })

      geoId = Geolocation.watchPosition({}, (newPosition, err) => {
        console.log('New GPS position: latitud ' + newPosition.coords.latitude + ' longitud ' + newPosition.coords.longitude)
        position.value = newPosition
        obtenerCodigoPostal(newPosition.coords.latitude, newPosition.coords.longitude)
      })
    }

    onBeforeUnmount(() => {
      Geolocation.clearWatch({ id: geoId })
    })

    return {
      position,
      codigoPostal,
      getCurrentPosition
    }
  }
}
</script>
