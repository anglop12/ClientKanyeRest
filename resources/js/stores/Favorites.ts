import { defineStore } from 'pinia'
import axios from '../axios'


type Quote = {
    quote: string
}

type Favorite = {
    id: number
    quote: string
}

type State = {
    quotes: Favorite[]
    loading: boolean
}

export const useFavorite = defineStore('favorite', {

  state: (): State => ({
    quotes: [],
    loading: false
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
            .catch(() => {
                this.quotes = [];
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
            .catch(() => {
                this.quotes = [];
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
            .catch(() => {
                this.quotes = [];
            })
            .finally(() => {
                this.loading = false;
            });
        },
    },

})

