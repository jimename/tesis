import { boot } from 'quasar/wrappers'
import axios from 'axios'
import {useCounterStore}  from 'stores/example-store'
import VueApexCharts from 'vue3-apexcharts'
// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: process.env.API })

export default boot(({ app, router }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.use(VueApexCharts)
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$store = useCounterStore()
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file


  const token= localStorage.getItem('tokenTor')
  if(token){
    api.defaults.headers.common['Authorization'] = 'Bearer '+token
    api.post('me').then((response)=>{
      // console.log(response.data)
      useCounterStore().user=response.data
      useCounterStore().permissions=response.data.permissions.map(permission => permission.name)
      useCounterStore().isLogged=true
    }).catch((error)=>{
      useCounterStore().isLogged=false
      router.push('/login')
      api.defaults.headers.common['Authorization'] = ''
      api.post('logout').then((response)=>{
        localStorage.removeItem('tokenTor')
      }).catch((error)=>{
        localStorage.removeItem('tokenTor')
      })
    })
  }else{
    router.push('/login')
    useCounterStore().isLogged=false
  }
  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }
