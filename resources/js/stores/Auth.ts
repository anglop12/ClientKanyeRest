import { defineStore } from 'pinia'
import axios from '../axios'

interface AuthLogin {
    email: string;
    password: string;
}

interface AuthRegister {
    name: string;
    email: string;
    password: string | null;
}

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

interface AuthState {
    user: User | null;
    auth: boolean;
    token: string;
    response: any;
}

export const useAuth = defineStore('auth', {

    state: (): AuthState => ({
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
        async logout() {
            await axios.post('api/logout').then(response => {
                this.user = null;
                this.token = '';
                this.auth = false;
                localStorage.removeItem('token');
            });
        },
        async editAuth(credentials : AuthRegister) {
            await axios.post(`api/editAuth/${this.user.id}`, credentials).then(response => {
                this.user = response.data.user;
            }).catch((error) => {
                this.response = error.response.data;
            });
        },
        async login(credentials : AuthLogin) {
            await axios.post('api/login', credentials).then(response => {
                this.token = response.data.access_token;
                localStorage.setItem('token', this.token);
                this.auth = Boolean(this.token);
                this.user = response.data.user;
            }).catch((error) => {
                this.response = error.response.data;
            });
        },
        async register(credentials : AuthRegister) {
            await axios.post('api/register', credentials).then(response => {
                this.token = response.data.access_token;
                localStorage.setItem('token', this.token);
                this.auth = Boolean(this.token);
                this.user = response.data.user;
            }).catch((error) => {
                this.response = error.response.data;
            });
        },
        async setAuth() {
            await axios.get('api/user')
            .then(response => {
                this.user = response.data;
            })
            .catch(() => {
                this.user = null;
                this.token = '';
                this.auth = false;
            });
        },
        async checkToken() {
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
                        await this.setAuth()
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

