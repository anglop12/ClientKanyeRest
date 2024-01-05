<template>
    <v-layout>
        <v-app-bar color="primary" prominent>
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

            <v-toolbar-title>Test Kanye West Quotes</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn class="ml-2" v-if="!authStore.getAuth" to="/register">
                Register
            </v-btn>

            <v-btn class="ml-2" v-if="!authStore.getAuth" to="/login">
                Login
            </v-btn>
        </v-app-bar>

        <v-navigation-drawer
            v-if="this.$route.name != 'login' && this.$route.name != 'register'"
            v-model="drawer"
            temporary
        >
            <v-list>
                <v-list-item
                :title="authStore.user?.name"
                :subtitle="authStore.user?.email"
                ></v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item to="/home" icon="mdi-folder" title="Home" value="Home"></v-list-item>
                <v-list-item v-if="authStore.user?.role == 'admin'" to="/auths" icon="mdi-star" title="Favorites" value="Favorites"></v-list-item>
                <v-list-item to="/quotes" icon="mdi-account-multiple" title="Quotes" value="Quotes"></v-list-item>
                <v-list-item to="/favorites" icon="mdi-star" title="Favorites" value="Favorites"></v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                <v-btn v-if="authStore.getAuth" @click="logout()" block color="grey-lighten-1">
                    Logout
                </v-btn>
                <v-btn to="/login" v-else block>
                    Login
                </v-btn>
                </div>
            </template>
        </v-navigation-drawer>

        <v-main style="height: 100dvh">
            <router-view />
        </v-main>
    </v-layout>
</template>

<script lang="ts">
    import { defineComponent } from 'vue';
    import { useAuth } from './stores/Auth';

    export default defineComponent({

        data() {
            return {
                authStore: useAuth(),
                drawer: false,
            }
        },

        async mounted() {
            if (!this.authStore.auth) {
                this.$router.push({ name: 'login' })
            }
        },

        methods: {
            async logout () {
                await this.authStore.logout()

                if (!this.authStore.auth) {
                    this.$router.push({ name: 'login' })
                }
            },
        }
    })
</script>
