<template>
    <v-breadcrumbs :items="['Favorites']"></v-breadcrumbs>
    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto">

                    <v-card-title>

                        <v-toolbar color="rgba(0, 0, 0, 0)">

                            <v-toolbar-title class="text-h6">
                                My favorites quotes
                            </v-toolbar-title>

                        </v-toolbar>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-table fixed-header height="400px">
                        <thead>
                            <tr>
                                <th class="text-left"> Quotes </th>
                                <th class="text-right"> Delete </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in favoriteStore.quotes" :key="item.id">
                                <td>{{ item.quote }}</td>
                                <td class="text-right"><v-btn icon="delete" size="x-small" @click="deleteFavorites(item)"></v-btn></td>
                            </tr>
                        </tbody>
                    </v-table>

                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script setup lang="ts">

    import { onMounted } from 'vue';
    import { useFavorite } from '../stores/Favorites';

    type Favorite = {
        id: number
        quote: string
        user_id: number
        user_name: string
    }

    const favoriteStore = useFavorite();

    const deleteFavorites = async (quote : Favorite ) => {
        await favoriteStore.deleteFavorites(quote)
        await favoriteStore.getFavorites()
    };

    onMounted(async () => {
        await favoriteStore.getFavorites()
    });

</script>
