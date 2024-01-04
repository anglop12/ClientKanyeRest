import './bootstrap';
import {createApp} from 'vue'
import { useUser } from './stores/user';
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// Vuetify
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, md } from 'vuetify/iconsets/md'

const vuetify = createVuetify({
    icons: {
        defaultSet: 'md',
        aliases,
        sets: {
          md,
        },
    },
    components,
    directives,
})

const pinia = createPinia()
const app = createApp(App)
app.use(vuetify)
app.use(router)
app.use(pinia)
const main = useUser()

router.beforeEach((to) => {
    // ✅ Esto funcionará si te aseguras que usas el almacén
    // correcto en la aplicación que está siendo ejecutada

    if (to.meta.requiresAuth && !main.auth) return '/login'
})


app.mount("#app")
