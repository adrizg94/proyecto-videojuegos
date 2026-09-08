<!-- Perfil, iniciar sesión, ajustes -->

<template>
  <header class="w-full flex justify-end items-center h-15.5 p-2">
    <NuxtLink
      v-if="!isAuthenticated"
      to="/login"
      class="px-2 py-1 text-center text-lg font-semibold rounded-lg cursor-pointer hover:bg-hover transition-colors"
    >
      <font-awesome-icon icon="fa-user" />
      Login
    </NuxtLink>
    <div v-else class="flex">
      <NuxtLink
        to="/profile"
        class="px-2 py-1 text-center text-lg font-semibold rounded-lg cursor-pointer hover:bg-hover transition-colors"
      >
        <font-awesome-icon icon="fa-user" />
        {{ user.username }}
      </NuxtLink>

      <button
        @click="logout"
        class="flex items-center p-1 hover:cursor-pointer hover:text-primary-light transition-colors"
      >
        <FontAwesomeIcon icon="fa-right-from-bracket" class="text-xl" />
      </button>
    </div>
  </header>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const { isAuthenticated, user, logout } = useAuth();
</script>
