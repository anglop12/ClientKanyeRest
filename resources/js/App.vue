<template>
    <v-card>
      <v-layout>
        <v-navigation-drawer
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
        </v-navigation-drawer>

        <v-main style="height: 100dvh">
            <router-view />
        </v-main>
      </v-layout>
    </v-card>
</template>
