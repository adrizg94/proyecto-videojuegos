<template>
  <div class="relative">
    <form class="flex gap-2" @submit.prevent="searchGames">
      <div class="relative flex-1">
        <FontAwesomeIcon
          icon="fa-magnifying-glass"
          class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-text-muted"
        />
        <input
          v-model.trim="search"
          type="text"
          placeholder="Search a game..."
          class="w-full rounded-lg bg-background py-2 pl-9 pr-3 text-sm outline-none border border-white/10 focus:border-primary"
        />
      </div>

      <button
        type="submit"
        :disabled="loading || !search"
        class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
      >
        <FontAwesomeIcon
          v-if="loading"
          icon="fa-spinner"
          class="animate-spin"
        />
        <span v-else> Search </span>
      </button>
    </form>

    <div
      v-if="games.length"
      class="absolute z-20 mt-2 w-full overflow-hidden rounded-xl border border-white/10 bg-surface shadow-xl"
    >
      <NuxtLink
        v-for="game in games"
        :key="game.id"
        :to="`/community/games/${game.id}`"
        class="flex items-center gap-3 p-3 transition-colors hover:bg-hover"
        @click="clearResults"
      >
        <img
          :src="game.background_image ?? '/images/image-not-found.svg'"
          :alt="game.name"
          class="h-12 w-16 shrink-0 rounded object-cover"
        />

        <div class="min-w-0">
          <p class="truncate text-sm font-medium">
            {{ game.name }}
          </p>

          <p class="text-xs text-text-muted">
            {{ game.released ?? "Release date unknown" }}
          </p>
        </div>
      </NuxtLink>
    </div>

    <p
      v-if="searched && !loading && !games.length"
      class="mt-3 text-sm text-text-muted"
    >
      No games found.
    </p>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const search = ref("");
const games = ref([]);
const loading = ref(false);
const searched = ref(false);

const searchGames = async () => {
  if (!search.value) return;

  loading.value = true;
  searched.value = false;

  try {
    const response = await $fetch("/api/games", {
      query: {
        search: search.value,
        page_size: 5,
      },
    });

    games.value = response.results ?? [];
  } catch (error) {
    console.error("Error searching games:", error);
    games.value = [];
  } finally {
    loading.value = false;
    searched.value = true;
  }
};

const clearResults = () => {
  games.value = [];
  search.value = "";
  searched.value = false;
};
</script>
