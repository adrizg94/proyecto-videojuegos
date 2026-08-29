<template>
  <div class="flex items-center mx-auto h-20 max-w-sm">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${creatorsCount} creators...`"
    />
  </div>
  <Loading v-if="status === 'pending'" />
  <div v-else>
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-9 pt-2 px-5"
    >
      <div
        v-for="creator in creators"
        :key="creator.id"
        class="flex justify-center py-2"
      >
        <CreatorCard :creator="creator" :link="`/creators/${creator.id}`" />
      </div>
    </div>
    <Pagination
      class="mt-5 mb-40"
      :current-page="currentPage"
      :total-pages="totalPages"
      @change-page="changePage"
    />
  </div>
</template>

<script setup>
const creatorsCount = ref("");

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(creatorsCount, 24);

const { data, status } = await useFetch("/api/creators", {
  query: {
    page: currentPage,
    page_size: pageSize,

    search: searchQuery,
    search_precise: true,
  },
});

const creators = computed(() => data.value.results ?? []);

watchEffect(() => {
  creatorsCount.value = data.value?.count ?? 0;
});
</script>
