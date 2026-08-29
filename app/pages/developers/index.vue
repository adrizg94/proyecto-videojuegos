<template>
  <div class="flex items-center mx-auto h-20 max-w-sm">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${developersCount} developers...`"
    />
  </div>
  <Loading v-if="status === 'pending'" />
  <div v-else>
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-7 pt-2 px-5"
    >
      <div
        v-for="developer in developers"
        :key="developer.id"
        class="flex justify-center py-2"
      >
        <!-- <Card
          :image="developer.image_background"
          :name="developer.name"
          :link="`/developers/${developer.id}`"
        /> -->
        <PubliDevCard
          :entity="developer"
          :link="`/developers/${developer.id}`"
        />
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
const developersCount = ref("");

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(developersCount);

const { data, status } = await useFetch("/api/developers", {
  query: {
    page: currentPage,
    page_size: pageSize,

    search: searchQuery,
    search_precise: true,
  },
});

const developers = computed(() => data.value.results ?? []);

watchEffect(() => {
  developersCount.value = data.value?.count ?? 0;
});
</script>
