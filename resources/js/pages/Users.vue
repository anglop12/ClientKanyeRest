<template>
    <v-breadcrumbs :items="['Users']"></v-breadcrumbs>
    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto">

                    <v-card-title>

                        <v-toolbar color="rgba(0, 0, 0, 0)">

                            <v-toolbar-title class="text-h6">
                                Users
                            </v-toolbar-title>

                        </v-toolbar>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-table fixed-header height="400px">
                        <thead>
                            <tr>
                                <th class="text-left"> User </th>
                                <th class="text-right"> Show </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in userStore.users" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td class="text-right">
                                    <v-btn icon="visibility" size="x-small" @click="showUser(item)"></v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>

                </v-card>
            </v-sheet>
        </v-col>

        <v-col cols="auto">
            <v-dialog transition="dialog-top-transition" width="auto" persistent v-model="dialog">
                <v-card>

                    <v-toolbar color="primary" :title="`User: ${userShow.name}`"></v-toolbar>

                    <v-card-text>
                        <v-container>
                            <v-row dense>
                                <v-col>
                                    <v-table fixed-header height="300px">
                                        <thead>
                                            <tr>
                                                <th class="text-left"> Quotes </th>
                                                <th class="text-right"> Delete </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="favorite in userShow.favorites" :key="favorite.id">
                                                <td>{{ favorite.quote }}</td>
                                                <td class="text-right"><v-btn icon="delete" size="x-small" @click="deleteFavorites(favorite)"></v-btn></td>
                                            </tr>
                                        </tbody>
                                    </v-table>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions class="justify-end">
                        <v-btn class="ma-2" color="red" variant="tonal" @click="showDelete">Delete User</v-btn>
                        <v-btn variant="tonal" @click="dialog = false">Close</v-btn>
                    </v-card-actions>

                </v-card>
            </v-dialog>

            <v-dialog v-model="dialog2" width="auto">
                <v-card>
                    <v-toolbar color="red" title="Delete"></v-toolbar>
                    <v-card-text>
                        <div>Are you sure you want to delete this user?</div>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn color="red" variant="tonal" @click="deleteUser">Delete</v-btn>
                        <v-btn variant="tonal" @click="dialog2 = false">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="userStore.loading" :scrim="false" persistent width="auto">
                <v-card color="red">
                    <v-card-text>Please stand by
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-col>
    </v-row>
</template>

<script setup lang="ts">

    import { onMounted, ref } from 'vue';
    import { useUser } from '../stores/Users';
    import { useFavorite } from '../stores/Favorites';
    import { useAuth } from '../stores/Auth';
    import { useRouter } from 'vue-router';

    type Favorite = {
        id: number;
        quote: string;
        user_id: number;
        user_name: string;
    }

    type User = {
        id: number;
        name: string;
        email: string;
        role: string;
        favorites?: Favorite[];
    }

    const userStore = useUser();
    const authStore = useAuth();
    const router = useRouter();

    const dialog = ref(false);
    const dialog2 = ref(false);

    const userShow = ref<User>({
        id: 0,
        name: '',
        email: '',
        role: '',
    });


    const deleteUser = async () => {
        await userStore.deleteUser(userShow.value)
        await userStore.getUsers()
        dialog2.value = false;
        dialog.value = false;
    };

    const showUser = async (user : User ) => {
        dialog.value = true;
        userShow.value = user;
    };
    const showDelete = async () => {
        dialog2.value = true;
    };

    const favoriteStore = useFavorite();
    const deleteFavorites = async (quote : Favorite ) => {
        await favoriteStore.deleteFavorites(quote)
        await userStore.getUsers()
        userShow.value = userStore.users.find((element) => element.id == userShow.value.id)
    };

    onMounted(async () => {
        if (authStore.user?.role != 'admin') {
            router.push({ name: 'forbidden' })
        }
        await userStore.getUsers()
    });

</script>
