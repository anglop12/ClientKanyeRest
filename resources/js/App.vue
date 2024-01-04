<template>
    <v-layout>
        <v-navigation-drawer
            v-if="this.$route.name != 'login' && this.$route.name != 'register'"
            expand-on-hover
        >
          <v-list>
            <v-list-item
              prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
              title="Sandra Adams"
              subtitle="sandra_a88@gmailcom"
            ></v-list-item>
          </v-list>

          <v-divider></v-divider>

          <v-list density="compact" nav>
            <v-list-item to="/home" icon="mdi-folder" title="Home" value="Home"></v-list-item>
            <v-list-item to="/quotes" icon="mdi-account-multiple" title="Quotes" value="Quotes"></v-list-item>
            <v-list-item to="/favorites" icon="mdi-star" title="Favorites" value="Favorites"></v-list-item>
          </v-list>

          <template v-slot:append>
            <div class="pa-2">
              <v-btn v-if="userStore.getAuth" @click="logout()" block>
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
    import { useUser } from './stores/User';

    export default defineComponent({

        data() {
            return {
                userStore: useUser()
            }
        },

        async mounted() {
            if (!this.userStore.auth) {
                this.$router.push({ name: 'login' })
            }
        },

        methods: {
            async logout () {
                await this.userStore.logout()

                if (!this.userStore.auth) {
                    this.$router.push({ name: 'login' })
                }
            },
        }
    })
</script>
