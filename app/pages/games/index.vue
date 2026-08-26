<template>
  <div class="flex items-center mx-auto h-20 max-w-sm">
    <SearchBar v-model="searchText" :games-count="data.count" />
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
      v-model:open-dropdown="openDropdown"
      :options-genres="genres"
      :options-tags="tags"
      :options-platforms="platforms"
      :options-stores="stores"
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
  <div
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-7 pt-2 px-5"
  >
    <div v-for="game in games" :key="game.id" class="flex justify-center py-2">
      <Card :image="game.background_image" :name="game.name" :id="game.id" />
    </div>
  </div>
  <Pagination
    class="mt-5 mb-40"
    :current-page="currentPage"
    :total-pages="totalPages"
    @change-page="changePage"
  />
</template>

<script setup>
const pageSize = 20;
const searchText = ref("");
const searchQuery = ref("");
const currentPage = ref(1);

const selectedGenres = ref([]);
const selectedTags = ref([]);
const selectedPlatforms = ref([]);
const selectedStores = ref([]);
const selectedOrder = ref("");

const openDropdown = ref(null);

const genresQuery = computed(() => {
  return selectedGenres.value.length
    ? selectedGenres.value.join(",")
    : undefined;
});

const tagsQuery = computed(() => {
  return selectedTags.value.length ? selectedTags.value.join(",") : undefined;
});

const platformsQuery = computed(() => {
  return selectedPlatforms.value.length
    ? selectedPlatforms.value.join(",")
    : undefined;
});

const storesQuery = computed(() => {
  return selectedStores.value.length
    ? selectedStores.value.join(",")
    : undefined;
});

const { data } = await useFetch("/api/games", {
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
  },
});

const { data: genres } = await useFetch("/api/genres", {
  transform: (data) => data.results,
});

const { data: tags } = await useFetch("/api/tags", {
  transform: (data) => data.results,
});

const { data: platforms } = await useFetch("/api/platforms", {
  transform: (data) => data.results,
});

const { data: stores } = await useFetch("/api/stores", {
  transform: (data) => data.results,
});

const games = computed(() => data.value?.results ?? []);

const totalPages = computed(() =>
  Math.ceil((data.value?.count ?? 0) / pageSize),
);

const hasFilters = computed(
  () =>
    selectedGenres.value.length > 0 ||
    selectedTags.value.length > 0 ||
    selectedPlatforms.value.length > 0 ||
    selectedStores.value.length > 0,
);

const changePage = (page) => {
  currentPage.value = page;
};

const clearFilters = () => {
  selectedGenres.value = [];
  selectedTags.value = [];
  selectedPlatforms.value = [];
  selectedStores.value = [];
  currentPage.value = 1;
};

watch(searchText, (newSearch, _, onCleanup) => {
  const timeout = setTimeout(() => {
    currentPage.value = 1;
    searchQuery.value = newSearch.trim();
  }, 400);

  onCleanup(() => {
    clearTimeout(timeout);
  });
});

watch(
  [genresQuery, tagsQuery, platformsQuery, storesQuery, selectedOrder],
  () => {
    currentPage.value = 1;
  },
);
</script>
