<!-- Perfil, iniciar sesión, ajustes -->

<template>
  <header class="w-full flex items-center h-15.5 p-2">
    <Breadcrumbs class="mt-2 ml-5" />
    <div class="ml-auto">

      <!-- Not authenticated -->
      <NuxtLink
        v-if="!isAuthenticated"
        to="/login"
        class="px-2 py-1 text-center text-lg font-semibold rounded-lg cursor-pointer hover:bg-hover transition-colors"
      >
        <FontAwesomeIcon icon="fa-user" />
        Login
      </NuxtLink>

      <!-- Authenticated -->
      <div v-else class="relative flex">
        <button
          type="button"
          @click="showOptions = !showOptions"
          class="px-2 py-1 text-center text-lg font-semibold rounded-lg cursor-pointer hover:bg-hover transition-colors"
        >
          <FontAwesomeIcon icon="fa-user" />
          {{ user.username }}

          <FontAwesomeIcon
            :icon="showOptions ? 'fa-chevron-up' : 'fa-chevron-down'"
          />
        </button>
        <UserMenu
          v-if="showOptions"
          :show-options="showOptions"
          @close="showOptions = false"
          class="absolute right-0 top-12 z-50 mt-2 w-40 rounded-xl bg-surface p-2 shadow-xl"
        />
      </div>
    </div>
  </header>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import UserMenu from "./users/UserMenu.vue";

const showOptions = ref(false);

const { isAuthenticated, user } = useAuth();
</script>
