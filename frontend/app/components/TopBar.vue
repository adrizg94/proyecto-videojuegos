<!-- Perfil, iniciar sesión, ajustes -->

<template>
  <header class="w-full flex justify-end items-center h-15.5 p-2">
      <NuxtLink to="/register"
        class="px-2 py-1 text-center text-lg font-semibold rounded-lg cursor-pointer hover:bg-hover transition"
      >
        <font-awesome-icon icon="fa-user" />
        Login
      </NuxtLink>
  </header>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
</script>
