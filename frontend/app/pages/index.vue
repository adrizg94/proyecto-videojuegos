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
      @select-date="selectDate"
    />
  </div>

  <Loading v-if="status === 'pending'" />

  <div v-else class="relative">
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
          <Card
            :game="game"
            :link="`/games/${game.id}`"
            @toggle-favorite="handleFavorite(game)"
            :is-favorite="isAuthenticated && favoriteIds?.includes(game.id)"
            @toggle-release-alert="handleReleaseAlert(game)"
            :is-release-alert="
              isAuthenticated && releaseAlertIds?.includes(game.id)
            "
          />
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

    <Chatbot />
  </div>

  <Toast />
</template>

<script setup>
import Card from "~/components/Card.vue";
import { useReleaseTimeline } from "~/composables/useReleaseTimeline";

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "Releases",
  },
]);

/*
|--------------------------------------------------------------------------
| Catalog pagination
|--------------------------------------------------------------------------
*/

const pageSize = 8;
const gamesCount = ref(0);

const { currentPage, totalPages, changePage } = useCatalog(gamesCount);

/*
|--------------------------------------------------------------------------
| Release timeline
|--------------------------------------------------------------------------
*/

const {
  selectedPeriod,
  currentDate,
  dateRange,
  changePeriod,
  nextPeriod,
  previousPeriod,
  goToToday,
  selectDate,
} = useReleaseTimeline();

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Games
|--------------------------------------------------------------------------
*/

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

const games = computed(() => data.value?.results ?? []);

/*
|--------------------------------------------------------------------------
| User game relations
|--------------------------------------------------------------------------
*/

const { isAuthenticated } = useAuth();

const { favoriteIds, toggleFavorite } = useFavorites();

const { releaseAlertIds, toggleReleaseAlert } = useReleaseAlerts();

const handleFavorite = (game) => {
  toggleFavorite(
    favoriteIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );
};

const handleReleaseAlert = (game) => {
  toggleReleaseAlert(
    releaseAlertIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );
};

/*
|--------------------------------------------------------------------------
| Watchers
|--------------------------------------------------------------------------
*/

// Volver a la página inicial cuando se cambia el periodo.
watch([selectedPeriod, currentDate], () => {
  currentPage.value = 1;
});

// Actualizar el total después de que useFetch haya definido los datos.
watchEffect(() => {
  gamesCount.value = data.value?.count ?? 0;
});
</script>
