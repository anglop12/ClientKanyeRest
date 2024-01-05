<template>
    <v-breadcrumbs :items="['Home']"></v-breadcrumbs>

    <v-row justify="center">
        <v-col cols="12">
            <v-sheet class="flex-1-1-100 ma-2 pa-2">
                <v-card class="mx-auto">

                    <v-card-title>

                        <v-toolbar color="rgba(0, 0, 0, 0)">

                            <v-toolbar-title class="text-h6">
                                Edit Profile
                            </v-toolbar-title>

                        </v-toolbar>
                    </v-card-title>

                    <v-divider></v-divider>


                    <v-card class="mx-auto px-6 py-8" max-width="344">
                        <v-form v-model="form" @submit.prevent="onEditUser">

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

                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script lang="ts">
    import { defineComponent } from 'vue';
    import { useUser } from '../stores/User';

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

                user: null,

                userStore: useUser()
            }
        },

        async mounted() {
            this.name = this.userStore.user.name
            this.email = this.userStore.user.email
        },

        watch: {
        },

        methods: {
            async onEditUser () {
                if (!this.form) return

                this.loading = true
                let data = {
                    name: this.name,
                    email: this.email,
                    password: this.password || null
                }

                await this.userStore.editUser(data)

                if (this.userStore.response !== null) {
                    this.alert = true;
                    this.msg = this.userStore.response.message;
                }
                this.loading = false;
                this.userStore.restoreRponse()
            },
            required (v) {
                return !!v || 'Field is required'
            },
        }
    })
</script>

