<template>
  <p class="text-white">Próximos lanzamientos</p>
  <div class="flex items-center mx-auto h-20 max-w-sm">
    <SearchBar v-model="searchText" />
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
    :currentPage="currentPage"
    :totalPages="totalPages"
    @changePage="changePage"
  />
</template>

<script setup>
const pageSize = 20;
const searchText = ref("");
const searchQuery = ref("");
const currentPage = ref(1);

const { data } = await useFetch("/api/games", {
  query: {
    page: currentPage,
    page_size: pageSize,
    search: searchQuery,
    search_precise: true,
  },
});

const games = computed(() => data.value?.results ?? []);

const totalPages = computed(() =>
  Math.ceil((data.value?.count ?? 0) / pageSize),
);

// const searchGames = computed(() => {
//   return games.value.filter((game) =>
//     game.name.toLowerCase().includes(searchText.value.toLowerCase()),
//   );
// });

const changePage = (page) => {
  currentPage.value = page;
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
</script>
