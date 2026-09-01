<template>
  <IndexHero />
  <div class="flex justify-center items-center">
    <IndexCalendar
      :current-date="currentDate"
      :selectPeriod="selectedPeriod"
      :genres="genres"
      :tags="tags"
      :platforms="platforms"
      :stores="stores"
      :developers="developers"
      :publishers="publishers"
      :clear-filters="clearFilters"
      :has-filters="hasFilters"
      v-model:selected-genres="selectedGenres"
      v-model:selected-tags="selectedTags"
      v-model:selected-platforms="selectedPlatforms"
      v-model:selected-stores="selectedStores"
      v-model:selected-developers="selectedDevelopers"
      v-model:selected-publishers="selectedPublishers"
      v-model:open-dropdown="openDropdown"
      @change-period="changePeriod"
      @go-to-today="goToToday"
    />
  </div>
  <Loading v-if="status === 'pending'" />
  <div v-else>
    <div class="grid grid-cols-[0.1fr_1fr_0.1fr]">
      <!-- Anterior periodo -->
      <button
        type="button"
        class="w-fit h-fit self-center cursor-pointer text-text-muted hover:text-primary-light transition-colors translate-x-20"
        aria-label="Previous date"
        @click="previousPeriod"
      >
        <FontAwesomeIcon icon="fa-arrow-left-long" class="text-3xl" />
      </button>
      <!-- Fichas de juegos -->
      <div
        class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 3xl:grid-cols-6 pt-2"
      >
        <div
          v-for="game in games"
          :key="game.id"
          class="flex justify-center py-5"
        >
          <GameCard :game="game" :link="`/games/${game.id}`" />
        </div>
      </div>
      <!-- Siguiente periodo -->
      <button
        type="button"
        class="w-fit h-fit self-center cursor-pointer text-text-muted hover:text-primary-light transition-colors translate-x-8"
        aria-label="Next date"
        @click="nextPeriod"
      >
        <FontAwesomeIcon icon="fa-arrow-right-long" class="text-3xl" />
      </button>
    </div>
    <Pagination
      v-if="games.length"
      class="my-5"
      :current-page="currentPage"
      :total-pages="totalPages"
      @change-page="changePage"
    />
  </div>
</template>

<script setup>
import { useReleaseTimeline } from "~/composables/useReleaseTimeline";

const pageSize = 8;
const gamesCount = ref(0);

const { currentPage, totalPages, changePage } = useCatalog(gamesCount);

const {
  selectedPeriod,
  currentDate,
  dateRange,
  changePeriod,
  nextPeriod,
  previousPeriod,
  goToToday,
} = useReleaseTimeline();

const {
  openDropdown,

  selectedGenres,
  selectedTags,
  selectedPlatforms,
  selectedStores,
  selectedDevelopers,
  selectedPublishers,

  genres,
  tags,
  platforms,
  stores,
  developers,
  publishers,

  genresQuery,
  tagsQuery,
  platformsQuery,
  storesQuery,
  developersQuery,
  publishersQuery,

  clearFilters,
  hasFilters,
} = useFilters(currentPage);

const { data, status } = await useFetch("/api/games", {
  query: {
    dates: computed(() => `${dateRange.value.start},${dateRange.value.end}`),
    ordering: "-added",
    page: currentPage,
    page_size: pageSize,

    genres: genresQuery,
    tags: tagsQuery,
    platforms: platformsQuery,
    stores: storesQuery,
    developers: developersQuery,
    publishers: publishersQuery,
  },
});

const games = computed(() => data.value.results);

// Volver a la página inicial cuando se cambia el periodo
watch([selectedPeriod, currentDate], () => {
  currentPage.value = 1;
});

// Cambiar estado despues de definir para evitar error is not defined
watchEffect(() => {
  gamesCount.value = data.value?.count ?? 0;
});
</script>

<!-- Querys dinámicos
const gamesQuery = computed(() => ({
  ordering: "-added",
  // exclude_additions: true,
  page: currentPage.value,
  page_size: pageSize,

  ...(selectedPeriod.value !== "All time" && {
    dates: `${dateRange.value.start},${dateRange.value.end}`,
  }),
}));
const { data, status } = await useFetch("/api/games", {
  query: gamesQuery,
}); -->
