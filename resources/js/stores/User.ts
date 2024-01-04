import { defineStore } from 'pinia'
import axios from '../axios'

interface User {
    name: string;
    email: string;
    role: string;
}

interface UserState {
    user: User | null;
    auth: boolean;
    token: string;
    response: any;
}

export const useUser = defineStore('user', {

    state: (): UserState => ({
        user: null,
        auth: false,
        token: '',
        response: null,
    }),

    getters: {
        getUser: (state) => state.user,
        getAuth: (state) => state.auth,
    },

    actions: {
        restoreRponse() {
            this.response = null;
        },
        logout() {
            axios.post('api/logout').then(response => {
                this.user = null;
                this.token = '';
                this.auth = false;
                localStorage.removeItem('token');
            });
        },
        async login(credentials : { user: string, password: string }) {
            await axios.post('api/login', credentials).then(response => {
                this.token = response.data.access_token;
                localStorage.setItem('token', this.token);
                this.auth = Boolean(this.token);
                this.user = response.data.user;
            }).catch((error) => {
                this.response = error.response.data;
            });
        },
        async setUser() {
            await axios.get('api/user')
            .then(response => {
                this.user = response.data.user;
            })
            .catch(() => {
                this.user = null;
                this.token = '';
                this.auth = false;
            });
        },
        checkToken() {
            const token = localStorage.getItem('token');
            if (token) {
                this.token = token;
                this.auth = true;
                const tokenPayload = JSON.parse(atob(token.split('.')[1]));

                if (tokenPayload && tokenPayload.exp) {
                    // Obtiene la marca de tiempo de expiracion del token en segundos
                    const tokenExp = tokenPayload.exp;

                    // Convierte la marca de tiempo de expiracion a milisegundos
                    const tokenExpDate = new Date(tokenExp * 1000);

                    // Obtiene la hora actual
                    const now = new Date();

                    // Verifica si el token ha expirado comparando con la hora actual
                    if (now >= tokenExpDate) {
                        this.user = null;
                        this.token = '';
                        this.auth = false;
                    } else {
                        this.setUser()
                    }
                } else {
                    this.user = null;
                    this.token = '';
                    this.auth = false;
                }
            } else {
                this.user = null;
                this.token = '';
                this.auth = false;
            }

        }
    },

})

