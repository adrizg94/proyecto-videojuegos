<template>
  <!-- Search -->
  <div class="flex items-center mx-auto h-20 max-w-sm mt-4 mb-1">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${gamesCount} games...`"
    />
  </div>

  <!-- Order and filters -->
  <div class="flex items-center gap-2 px-8">
    <GameOrderBy
      class="mr-10"
      v-model:selected="selectedOrder"
      v-model:open-dropdown="openDropdown"
    />

    <GameFilters
      v-model:genres="selectedGenres"
      v-model:tags="selectedTags"
      v-model:platforms="selectedPlatforms"
      v-model:stores="selectedStores"
      v-model:developers="selectedDevelopers"
      v-model:publishers="selectedPublishers"
      v-model:open-dropdown="openDropdown"
      :options-genres="genres"
      :options-tags="tags"
      :options-platforms="platforms"
      :options-stores="stores"
      :options-developers="developers"
      :options-publishers="publishers"
    />

    <button
      v-if="hasFilters"
      type="button"
      class="cursor-pointer rounded-lg my-2 px-2 py-1 text-text-muted hover:bg-hover hover:text-white"
      @click="clearFilters"
    >
      <FontAwesomeIcon icon="fa-filter-circle-xmark" />
      Clear Filters
    </button>
  </div>

  <Loading v-if="status === 'pending'" />

  <div v-else>
    <!-- Games -->
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-7 pt-2 px-5"
    >
      <div
        v-for="game in games"
        :key="game.id"
        class="flex justify-center py-3"
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

    <!-- Pagination -->
    <Pagination
      class="mt-5 mb-40"
      :current-page="currentPage"
      :total-pages="totalPages"
      @change-page="changePage"
    />

    <Chatbot />
    <Toast />
  </div>
</template>

<script setup>
/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "Games",
  },
]);

/*
|--------------------------------------------------------------------------
| Route
|--------------------------------------------------------------------------
*/

const route = useRoute();
const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const gamesCount = ref(0);

/*
|--------------------------------------------------------------------------
| Composables
|--------------------------------------------------------------------------
*/

const { favoriteIds, toggleFavorite } = useFavorites();
const { releaseAlertIds, toggleReleaseAlert } = useReleaseAlerts();
const { isAuthenticated } = useAuth();

/*
|--------------------------------------------------------------------------
| Initial filters from URL
|--------------------------------------------------------------------------
*/

// Recoge los filtros recibidos mediante query params,
// por ejemplo desde los enlaces de una ficha de juego.
const getQueryIds = (query) => {
  if (!query) return [];

  return String(query).split(",").map(Number);
};

const initialFilters = {
  genres: getQueryIds(route.query.genres),
  tags: getQueryIds(route.query.tags),
  platforms: getQueryIds(route.query.platforms),
  stores: getQueryIds(route.query.stores),
  developers: getQueryIds(route.query.developers),
  publishers: getQueryIds(route.query.publishers),
  creators: getQueryIds(route.query.creators),
};

/*
|--------------------------------------------------------------------------
| Catalog
|--------------------------------------------------------------------------
*/

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(gamesCount);

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const {
  openDropdown,
  selectedOrder,

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
  creatorsQuery,

  clearFilters,
  hasFilters,
} = useFilters(currentPage, initialFilters);

/*
|--------------------------------------------------------------------------
| Games
|--------------------------------------------------------------------------
*/

const { data, status } = await useFetch("/api/games", {
  query: {
    page: currentPage,
    page_size: pageSize,

    search: searchQuery,
    search_precise: true,

    genres: genresQuery,
    tags: tagsQuery,
    platforms: platformsQuery,
    stores: storesQuery,
    ordering: selectedOrder,

    developers: developersQuery,
    publishers: publishersQuery,
    creators: creatorsQuery,
  },
});

const games = computed(() => data.value?.results ?? []);

/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

const handleFavorite = (game) => {
  toggleFavorite(
    favoriteIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );
};

/*
|--------------------------------------------------------------------------
| Release alerts
|--------------------------------------------------------------------------
*/

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

watchEffect(() => {
  gamesCount.value = data.value?.count ?? 0;
});

/*
|--------------------------------------------------------------------------
| Page initialization
|--------------------------------------------------------------------------
*/

// Los filtros recibidos por URL ya se han guardado en initialFilters,
// por lo que eliminamos los query params de la dirección visible.
onMounted(() => {
  if (Object.keys(route.query).length) {
    router.replace({ path: "/games" });
  }
});
</script>
