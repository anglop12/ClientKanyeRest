<template>
    <v-breadcrumbs :items="['Favorites']"></v-breadcrumbs>
    <v-row justify="center">
        <v-col cols="12">
          <v-sheet class="flex-1-1-100 ma-2 pa-2">
              <v-card class="mx-auto">

                <v-card-item>
                    <v-card-title>

                        <v-toolbar color="rgba(0, 0, 0, 0)">

                            <v-toolbar-title class="text-h6">
                                My favorites
                            </v-toolbar-title>

                            <template v-slot:append>
                                <v-btn icon="refresh"></v-btn>
                            </template>

                        </v-toolbar>
                    </v-card-title>
                    <v-card-subtitle>
                        Card subtitle secondary text
                    </v-card-subtitle>
                </v-card-item>

                <v-card-text>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
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
    const favorites = ref([]);

    const getFavorites = async () => {
      // Replace this URL with your own API endpoint that returns Kayne West favorites
      const response = await fetch('https://api.example.com/kanye-favorites');
      const data = await response.json();
      favorites.value = data.slice(0, 5);
    };

    const deleteFavorite = (favorite: { quote: string } ) => {
      // Save the quote as a favorite here
      console.log('Saving quote as favorite:', favorite);
    };

    return { favorites, getFavorites, deleteFavorite };
 },
});
</script>
