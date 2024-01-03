<template>
    <v-breadcrumbs :items="['Quotes']"></v-breadcrumbs>

    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto">

                    <v-card-item>
                        <v-card-title>
                            <v-toolbar color="rgba(0, 0, 0, 0)">

                                <v-toolbar-title class="text-h5">
                                    Kanye West quotes
                                </v-toolbar-title>

                                <template v-slot:append>
                                    <v-btn icon="refresh"></v-btn>
                                </template>

                            </v-toolbar>
                        </v-card-title>
                    </v-card-item>

                    <v-card-text>
                        <v-container>
                            <v-row dense>
                                <v-col cols="12">
                                    <v-card class="mx-auto" color="#1F7087" variant="tonal">

                                        <template v-slot:title>This is a title</template>

                                        <template v-slot:append>
                                            <v-btn icon="star">
                                                <v-icon color="grey-lighten-3" icon="star"></v-icon>
                                            </v-btn>
                                        </template>

                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';

export default defineComponent({
 components: {
 },
 setup() {
    const quotes = ref([]);

    const getQuotes = async () => {
      // Replace this URL with your own API endpoint that returns Kayne West quotes
      const response = await fetch('https://api.example.com/kanye-quotes');
      const data = await response.json();
      quotes.value = data.slice(0, 5);
    };

    const saveFavorite = (quote : { quote: string }) => {
      // Save the quote as a favorite here
      console.log('Saving quote as favorite:', quote);
    };

    return { quotes, getQuotes, saveFavorite };
 },
});
</script>
