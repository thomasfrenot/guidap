import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import App from './App.vue'

import "primevue/resources/themes/mira/theme.css";     
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import Card from "primevue/card"
import InputText from "primevue/inputtext"
import MultiSelect from 'primevue/multiselect'
import ProgressSpinner from "primevue/progressspinner"
import Tag from "primevue/tag"

const app = createApp(App)
app.use(PrimeVue)

app.component('PrimeCard', Card)
app.component('InputText', InputText)
app.component('MultiSelect', MultiSelect)
app.component('ProgressSpinner', ProgressSpinner)
app.component('PrimeTag', Tag)

app.mount('#app')
