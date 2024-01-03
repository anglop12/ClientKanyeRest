import './bootstrap';
import {createApp} from 'vue'
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

createApp(App)
    .use(vuetify)
    .use(router)
    .mount("#app")
