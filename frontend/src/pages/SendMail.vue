<template>
  <q-page class="q-pa-md">
    <q-form @submit="submitForm">
      <q-input v-model="name" label="Nombre" required></q-input>
      <q-input v-model="email" label="Email" type="email" required></q-input>
      <q-input v-model="message" label="Mensaje" type="textarea" required></q-input>
      <q-btn label="Enviar" type="submit" color="primary"></q-btn>
    </q-form>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      message: ''
    }
  },
  methods: {
    async submitForm() {
      try {
        await this.$axios.post('http://localhost:8000/api/send-email', {
          name: this.name,
          email: this.email,
          message: this.message
        });
        this.$q.notify({
          type: 'positive',
          message: 'Correo enviado correctamente!'
        });
      } catch (error) {
        this.$q.notify({
          type: 'negative',
          message: 'Error al enviar el correo.'
        });
      }
    }
  }
}
</script>
