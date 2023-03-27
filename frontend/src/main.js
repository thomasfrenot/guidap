import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import App from './App.vue'

import "primevue/resources/themes/mira/theme.css";     
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import Button from "primevue/button"
import Card from "primevue/card"
import InputText from "primevue/inputtext"
import MultiSelect from 'primevue/multiselect'
import Tag from "primevue/tag"

const app = createApp(App)
app.use(PrimeVue)

app.component('Button', Button)
app.component('Card', Card)
app.component('InputText', InputText)
app.component('MultiSelect', MultiSelect)
app.component('Tag', Tag)

app.mount('#app')
