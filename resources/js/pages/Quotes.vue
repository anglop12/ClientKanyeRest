<template>
    <v-breadcrumbs :items="['Quotes']"></v-breadcrumbs>

    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto" max-width="850">

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
                        <v-card-subtitle v-if="quoteStore.error">
                            <v-alert
                                color="warning"
                                icon="$warning"
                                title="Warning"
                                :text="quoteStore.error.message + ' We recommend waiting 2 minutes.'"
                            ></v-alert>
                        </v-card-subtitle>
                    </v-card-item>

                    <v-card-text>
                        <v-table fixed-header height="400px">
                            <thead>
                                <tr>
                                    <th class="text-left"> Quotes </th>
                                    <th class="text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="quoteStore.loading" v-for="item in [1,2,3,4,5]">
                                    <td><v-skeleton-loader
                                        :loading="quoteStore.loading"
                                        type="list-item-two-line"
                                    >
                                        <v-list-item
                                            title="Title"
                                            subtitle="Subtitle"
                                            lines="two"
                                            rounded
                                        ></v-list-item>
                                    </v-skeleton-loader></td>
                                    <td></td>
                                </tr>
                                <tr v-else v-for="(item, index) in quoteStore.quotes" :key="index">
                                    <td>{{ item.quote }}</td>
                                    <td class="text-right">
                                        <v-btn :disabled="favoriteStore.quotes.findIndex((element) => element.quote == item.quote) >= 0 ? true : false" @click="saveFavorites(item)" icon="star">
                                            <v-icon :color="favoriteStore.quotes.findIndex((element) => element.quote == item.quote) === -1 ? 'grey-lighten-3' : 'yellow'" icon="star"></v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
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
import { useAuth } from '../stores/Auth';

const quoteStore = useQuote();
const favoriteStore = useFavorite();
const authStore = useAuth();

const refreshQuotes = async () => {
    quoteStore.loading = true;
    for (let index = 0; index < 5; index++) {
        await quoteStore.getOneQuote();
    }
    quoteStore.loading = false;
};

const saveFavorites = async (quote : { quote: string } ) => {

    let data = {
        "quote": quote.quote,
        "user_id": authStore.user?.id
    }
    await favoriteStore.setFavorites(data)
    await favoriteStore.getFavorites()
};

onMounted(async () => {
    await favoriteStore.getFavorites()
    await quoteStore.getFiveQuotes()
});

</script>
