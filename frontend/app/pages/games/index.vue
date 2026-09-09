<template>
  <div class="flex items-center mx-auto h-20 max-w-sm mt-4 mb-1">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${gamesCount} games...`"
    />
  </div>
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
          @add-favorite="addFavorite"
          @remove-favorite="removeFavorite"
          @toggle-favorite="handleFavorite(game)"
          :is-favorite="isAuthenticated && favoriteIds?.includes(game.id)"
        />
      </div>
    </div>
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
const route = useRoute();
const router = useRouter();
const gamesCount = ref(0);
const { favoriteIds, addFavorite, removeFavorite, toggleFavorite } =
  useFavorites();
const { isAuthenticated } = useAuth();

// Función que recoge las querys de la url para buscar por filtros
// mediante los enlaces de las fichas de juego
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

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(gamesCount);

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

const handleFavorite = (game) => {
  toggleFavorite(
    favoriteIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );
};

// Cambiar estado despues de definir para evitar error is not defined
watchEffect(() => {
  gamesCount.value = data.value?.count ?? 0;
});

// Limpiamos la url cuando pasamos querys
onMounted(() => {
  if (Object.keys(route.query).length) {
    router.replace({ path: "/games" });
  }
});
</script>
