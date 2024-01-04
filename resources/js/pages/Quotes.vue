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
                                    <v-btn @click="refreshQuotes()" icon="refresh"></v-btn>
                                </template>

                            </v-toolbar>
                        </v-card-title>
                    </v-card-item>

                    <v-card-text>
                        <v-container>
                            <v-row dense>
                                <v-col cols="12" v-for="(item, index) in quoteStore.quotes" :key="index" >
                                    <v-card v-if="!quoteStore.loading" class="mx-auto" color="#1F7087" variant="tonal">

                                        <template v-slot:title>{{item.quote}}</template>

                                        <template v-slot:append>
                                            <v-btn :disabled="favoriteStore.quotes.findIndex((element) => element.quote == item.quote) >= 0 ? true : false" @click="saveFavorites(item)" icon="star">
                                                <v-icon :color="favoriteStore.quotes.findIndex((element) => element.quote == item.quote) === -1 ? 'grey-lighten-3' : 'yellow'" icon="star"></v-icon>
                                            </v-btn>
                                        </template>

                                    </v-card>
                                    <v-card v-else class="mx-auto" color="#1F7087" variant="tonal">
                                        <v-card-text>
                                            <v-skeleton-loader
                                                :loading="quoteStore.loading"
                                                type="list-item-two-line"
                                            >
                                                <v-list-item
                                                    title="Title"
                                                    subtitle="Subtitle"
                                                    lines="two"
                                                    rounded
                                                ></v-list-item>
                                            </v-skeleton-loader>
                                        </v-card-text>

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

<script setup lang="ts">
import { onMounted } from 'vue';
import { useQuote } from '../stores/Quotes';
import { useFavorite } from '../stores/Favorites';

const quoteStore = useQuote();
const favoriteStore = useFavorite();

const refreshQuotes = async () => {
    quoteStore.loading = true;
    for (let index = 0; index < 5; index++) {
        await quoteStore.getOneQuote();
    }
    quoteStore.loading = false;
};

const saveFavorites = async (quote : { quote: string } ) => {
    await favoriteStore.setFavorites(quote)
    await favoriteStore.getFavorites()
};

onMounted(async () => {
    await favoriteStore.getFavorites()
    await quoteStore.getFiveQuotes()
});

</script>
