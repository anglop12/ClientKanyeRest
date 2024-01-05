<template>
    <v-sheet class="pa-12" rounded>
        <v-card class="mx-auto px-6 py-8" max-width="344">
            <v-form v-model="form" @submit.prevent="onSubmit">
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
                    :rules="[required]"
                    clearable
                    label="Password"
                    placeholder="Enter your password"
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
                    Login
                </v-btn>
            </v-form>
        </v-card>
    </v-sheet>
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
                email: null,
                password: null,
                loading: false,

                authStore: useAuth()
            }
        },

        async mounted() {
            if (this.authStore.auth) {
                this.$router.push({ name: 'home' })
            }
        },

        watch: {
        },

        methods: {
            async onSubmit () {
                if (!this.form) return

                this.loading = true
                let data = {
                    email: this.email,
                    password: this.password
                }

                await this.authStore.login(data)

                if (this.authStore.response !== null) {
                    this.alert = true;
                    this.msg = this.authStore.response.message;
                } else {
                    this.$router.push({ name: 'home' })
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
