<template>
    <v-breadcrumbs :items="['Home']"></v-breadcrumbs>

    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto" max-width="850">

                    <v-card-title>

                        <v-toolbar color="rgba(0, 0, 0, 0)">

                            <v-toolbar-title class="text-h6">
                                Edit Profile
                            </v-toolbar-title>

                        </v-toolbar>
                    </v-card-title>

                    <v-divider></v-divider>


                    <v-form v-model="form" @submit.prevent="onEditAuth" class="mx-auto px-6 py-8" max-width="450">

                        <v-text-field
                            v-model="name"
                            :readonly="loading"
                            :rules="[required]"
                            class="mb-2"
                            clearable
                            label="Name"
                        ></v-text-field>

                        <v-text-field
                            v-model="email"
                            :readonly="loading"
                            :rules="[required]"
                            class="mb-2"
                            clearable
                            label="Email"
                        ></v-text-field>

                        <v-text-field
                            v-model="password"
                            :readonly="loading"
                            clearable
                            label="New Password"
                            placeholder="New password"
                        ></v-text-field>

                        <br>

                        <v-alert
                            v-if="alert"
                            type="error"
                            closable
                            icon="error"
                            :text="msg"
                        ></v-alert>

                        <br>

                        <v-btn
                            :disabled="!form"
                            :loading="loading"
                            block
                            color="success"
                            size="large"
                            type="submit"
                            variant="elevated"
                        >
                            Save
                        </v-btn>
                    </v-form>

                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script lang="ts">
    import { defineComponent } from 'vue';
    import { useAuth } from '../stores/Auth';

    export default defineComponent({

        components: {
        },

        data() {
            return {
                alert: false,
                msg: '',

                form: false,
                name: null,
                email: null,
                password: null,
                loading: false,

                auth: null,

                authStore: useAuth()
            }
        },

        async mounted() {
            this.name = this.authStore.user.name
            this.email = this.authStore.user.email
        },

        watch: {
        },

        methods: {
            async onEditAuth () {
                if (!this.form) return

                this.loading = true
                let data = {
                    name: this.name,
                    email: this.email,
                    password: this.password || null
                }

                await this.authStore.editAuth(data)

                if (this.authStore.response !== null) {
                    this.alert = true;
                    this.msg = this.authStore.response.message;
                }
                this.loading = false;
                this.authStore.restoreRponse()
            },
            required (v) {
                return !!v || 'Field is required'
            },
        }
    })
</script>

