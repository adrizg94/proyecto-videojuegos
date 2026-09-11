<template>
  <!-- Search -->
  <div class="flex items-center mx-auto h-20 max-w-sm mt-4">
    <SearchBar
      v-model="searchText"
      :placeholder="`Search ${creatorsCount} creators...`"
    />
  </div>

  <Loading v-if="status === 'pending'" />

  <div v-else>
    <!-- Creators -->
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-9 pt-2 px-5"
    >
      <div
        v-for="creator in creators"
        :key="creator.id"
        class="flex justify-center py-4"
      >
        <CreatorCard
          :creator="creator"
          :link="`/creators/${creator.id}`"
          @toggle-favorite="handleCreatorFavorite(creator)"
          :is-favorite="isAuthenticated && creatorNames.includes(creator.name)"
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

    <Toast />
  </div>
</template>

<script setup>
import { useCreatorFavorites } from "~/composables/entities/useCreatorsFavorites";

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "Creators",
  },
]);

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const creatorsCount = ref("");

/*
|--------------------------------------------------------------------------
| Composables
|--------------------------------------------------------------------------
*/

const { isAuthenticated } = useAuth();

const { creatorNames, toggleCreator } = useCreatorFavorites();

const {
  currentPage,
  pageSize,
  searchText,
  searchQuery,
  totalPages,
  changePage,
} = useCatalog(creatorsCount, 24);

/*
|--------------------------------------------------------------------------
| Creators
|--------------------------------------------------------------------------
*/

const { data, status } = await useFetch("/api/creators", {
  query: {
    page: currentPage,
    page_size: pageSize,

    search: searchQuery,
    search_precise: true,
  },
});

const creators = computed(() => data.value?.results ?? []);

/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

const handleCreatorFavorite = (creator) => {
  toggleCreator(
    creatorNames.value.includes(creator.name),
    creator.id,
    creator.name,
    creator.image,
  );
};

/*
|--------------------------------------------------------------------------
| Watchers
|--------------------------------------------------------------------------
*/

watchEffect(() => {
  creatorsCount.value = data.value?.count ?? 0;
});
</script>
