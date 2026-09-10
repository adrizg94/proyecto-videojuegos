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
        <Card
          :entity="publisher"
          :link="`/publishers/${publisher.id}`"
          @add-favorite="addPublisher"
          @remove-favorite="removePublisher"
          @toggle-favorite="handlePublisherFavorite(publisher)"
          :is-favorite="
            isAuthenticated && publisherNames.includes(publisher.name)
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
import { usePublisherFavorites } from '~/composables/entities/usePublishersFavorites';


const publishersCount = ref("");

// const { apiFetch } = useApi();
const { isAuthenticated } = useAuth();
const { publisherNames, addPublisher, removePublisher, togglePublisher } =
  usePublisherFavorites();

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

// const { data: favoritePublisherIds } = await useAsyncData(
//   "favorite-publisher-ids",
//   async () => {
//     const data = await apiFetch("publishers");

//     return data.map((publisher) => publisher.rawg_id);
//   },
//   {
//     server: false,
//     default: () => [],
//   },
// );

const handlePublisherFavorite = (publisher) => {
  togglePublisher(
    publisherNames.value.includes(publisher.name),
    publisher.id,
    publisher.name,
    publisher.image_background,
  );
};

const publishers = computed(() => data.value.results ?? []);

watchEffect(() => {
  publishersCount.value = data.value?.count ?? 0;
});
</script>
