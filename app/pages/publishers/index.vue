<template>
  <div class="flex items-center mx-auto h-20 max-w-sm mt-4">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${publishersCount} publishers...`"
    />
  </div>
  <Loading v-if="status === 'pending'" />
  <div v-else>
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-7 pt-2 px-5"
    >
      <div
        v-for="publisher in publishers"
        :key="publisher.id"
        class="flex justify-center py-3"
      >
        <!-- <Card
            :image="publisher.image_background"
            :name="publisher.name"
            :link="`/publishers/${publisher.id}`"
          /> -->
        <PubliDevCard
          :entity="publisher"
          :link="`/publishers/${publisher.id}`"
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
const publishersCount = ref("");

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(publishersCount);

const { data, status } = await useFetch("/api/publishers", {
  query: {
    page: currentPage,
    page_size: pageSize,

    search: searchQuery,
    search_precise: true,
  },
});

const publishers = computed(() => data.value.results ?? []);

watchEffect(() => {
  publishersCount.value = data.value?.count ?? 0;
});
</script>
