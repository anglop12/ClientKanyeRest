import { defineStore } from 'pinia'
import axios from '../axios'


type Quote = {
    quote: string
    user_id: number
}

type Favorite = {
    id: number
    quote: string
    user_id: number
}

type State = {
    quotes: Favorite[]
    loading: boolean
    error: any
}

export const useFavorite = defineStore('favorite', {

  state: (): State => ({
    quotes: [],
    loading: false,
    error: false
  }),

  getters: {
  },

  actions: {
        async getFavorites() {
            this.loading = true;
            await axios.get('api/favorites')
            .then(response => {
                this.quotes = response.data;
            })
            .catch(error => {
                this.error = error.response.data;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        async setFavorites(quote: Quote) {
            this.loading = true;
            let data : Quote = quote;
            await axios.post('api/favorites', data)
            .then(response => {
                this.quotes.push(response.data);
            })
            .catch(error => {
                this.error = error.response.data;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        async deleteFavorites(quote: Favorite) {
            this.loading = true;
            await axios.delete(`api/favorites/${quote.id}`)
            .then(response => {
                this.quotes = response.data;
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

