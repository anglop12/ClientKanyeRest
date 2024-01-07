import { defineStore } from 'pinia'
import axios from '../axios'

type Quote = {
    quote: string
}

type State = {
    quotes: Quote[]
    quote: Quote,
    loading: boolean
    error: any
}

export const useQuote = defineStore('quote', {

  state: (): State => ({
    quotes: [],
    quote: {quote:''},
    loading: false,
    error: false
  }),

  getters: {
    getQuotes: (state) => state.quotes,
    getQuote: (state) => state.quote,
  },

  actions: {
    async getOneQuote() {
        this.error = false
        await axios.get('api/quote')
        .then(response => {
            this.quote = response.data;
            this.quotes.push(this.quote);
            this.quotes.shift();
        })
        .catch(error => {
            this.error = error.response.data;
        });
    },

    async getFiveQuotes() {
        this.error = false
        this.loading = true;
        await axios.get('api/quotes/5')
        .then(response => {
            this.quotes = response.data;
        })
        .catch(error => {
            this.error = error.response.data;
        })
        .finally(() => {
            this.loading = false;
        });
    }
},

})

