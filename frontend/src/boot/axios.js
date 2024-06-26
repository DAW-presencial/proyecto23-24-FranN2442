import { boot } from 'quasar/wrappers'
import axios from 'axios'
import { Loading, Notify } from 'quasar'

const apiUrl = 'https://booknow-api.randion.es/api/v1'
// const apiUrl = 'http://127.0.0.1:8000/api/v1'

const api = axios.create({  baseURL: 'http://127.0.0.1:8000/api/v1' ,
                            headers : { post : {
                                            'Accept' : 'application/vnd.api+json',
                                            'Content-Type' : 'application/vnd.api+json'},
                                        get : {
                                            'Accept' : 'application/vnd.api+json'
                                        }} })

export default boot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

api.interceptors.request.use(function (config){

    return config

})

api.interceptors.response.use(function (response){

    return response

})


export { axios, api , apiUrl }
