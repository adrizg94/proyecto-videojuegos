<template>
  <div class="flex items-center mx-auto h-20 max-w-sm mt-4">
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
        class="flex justify-center py-3"
      >
        <!-- <Card
          :image="developer.image_background"
          :name="developer.name"
          :link="`/developers/${developer.id}`"
        /> -->
        <Card
          :entity="developer"
          :link="`/developers/${developer.id}`"
          @add-favorite="addDeveloper"
          @remove-favorite="removeDeveloper"
          @toggle-favorite="handleDeveloperFavorite(developer)"
          :is-favorite="
            isAuthenticated && developerNames.includes(developer.name)
          "
        />
      </div>
    </div>
    <Pagination
      class="mt-5 mb-40"
      :current-page="currentPage"
      :total-pages="totalPages"
      @change-page="changePage"
    />
    <Toast />
  </div>
</template>

<script setup>
import { useDeveloperFavorites } from "~/composables/entities/useDevelopersFavorites";

const developersCount = ref("");

const { isAuthenticated } = useAuth();

const { developerNames, addDeveloper, removeDeveloper, toggleDeveloper } =
  useDeveloperFavorites();

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

const handleDeveloperFavorite = (developer) => {
  toggleDeveloper(
    developerNames.value.includes(developer.name),
    developer.name,
    developer.image_background,
  );
};

watchEffect(() => {
  developersCount.value = data.value?.count ?? 0;
});
</script>
