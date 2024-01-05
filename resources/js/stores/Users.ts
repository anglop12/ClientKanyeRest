import { defineStore } from 'pinia'
import axios from '../axios'
import { useAuth } from './Auth'

type Favorite = {
    id: number
    quote: string
    user_id: number
}

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    favorites?: Favorite[];
}

interface UserState {
    users: User[];
    loading: boolean
    error: any
}


const authStore = useAuth()

export const useUser = defineStore('users', {

    state: (): UserState => ({
        users: [],
        loading: false,
        error: false
    }),

    getters: {
        getUser: (state) => state.users,
    },

    actions: {
        restoreRponse() {
            this.response = null;
        },
        async getUsers() {
            await axios.get('api/users').then(response => {
                this.users = response.data.filter((element) => element.id != authStore.user?.id);
            });
        },
        async editUser(user : User) {
            this.loading = true;
            await axios.put(`api/user/${user.id}`, user).then(response => {
                this.user = response.data.user;
            }).catch((error) => {
                this.response = error.response.data;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        async deleteUser(user : User) {
            this.loading = true;
            await axios.delete(`api/user/${user.id}`)
            .then(response => {
                this.getUsers();
            })
            .catch(error => {
                this.error = error.response.data;
            })
            .finally(() => {
                this.loading = false;
            });
        },
    },

})

