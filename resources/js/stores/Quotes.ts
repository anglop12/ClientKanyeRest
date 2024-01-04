import { defineStore } from 'pinia'
import axios from '../axios'

type Quote = {
    quote: string
}

type State = {
    quotes: Quote[]
    quote: Quote,
    loading: boolean
}

export const useQuote = defineStore('quote', {

  state: (): State => ({
    quotes: [],
    quote: {quote:''},
    loading: false
  }),

  getters: {
    getQuotes: (state) => state.quotes,
    getQuote: (state) => state.quote,
  },

  actions: {
    async getOneQuote() {
        await axios.get('api/quote')
        .then(response => {
            this.quote = response.data;
            this.quotes.push(this.quote);
            this.quotes.shift();
        })
        .catch(() => {
            this.quotes = [];
        });
    },

    async getFiveQuotes() {
        this.loading = true;
        await axios.get('api/quotes/5')
        .then(response => {
            this.quotes = response.data;
        })
        .catch(() => {
            this.quotes = [];
        })
        .finally(() => {
            this.loading = false;
        });
    }
},

})

