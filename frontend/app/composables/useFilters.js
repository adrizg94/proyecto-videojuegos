export const useFilters = (currentPage, initialFilters = {}) => {
  const openDropdown = ref(null);
  const selectedOrder = ref("");

  // Arrays para guardar el valor de los filtros
  const selectedGenres = ref(initialFilters.genres ?? []);
  const selectedTags = ref(initialFilters.tags ?? []);
  const selectedPlatforms = ref(initialFilters.platforms ?? []);
  const selectedStores = ref(initialFilters.stores ?? []);
  const selectedDevelopers = ref(initialFilters.developers ?? []);
  const selectedPublishers = ref(initialFilters.publishers ?? []);
  const selectedCreators = ref(initialFilters.creators ?? []);

  const { data: genres } = useFetch("/api/genres", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: tags } = useFetch("/api/tags", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: platforms } = useFetch("/api/platforms", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: stores } = useFetch("/api/stores", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: developers } = useFetch("/api/developers", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: publishers } = useFetch("/api/publishers", {
    transform: (data) => data.results,
    default: () => [],
  });
  const { data: creators } = useFetch("/api/creators", {
    transform: (data) => data.results,
    default: () => [],
  });

  // Computeds para construir las querys del fetch
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
  const developersQuery = computed(() =>
    selectedDevelopers.value.length
      ? selectedDevelopers.value.join(",")
      : undefined,
  );
  const publishersQuery = computed(() =>
    selectedPublishers.value.length
      ? selectedPublishers.value.join(",")
      : undefined,
  );
  const creatorsQuery = computed(() =>
    selectedCreators.value.length
      ? selectedCreators.value.join(",")
      : undefined,
  );

  const clearFilters = () => {
    selectedGenres.value = [];
    selectedTags.value = [];
    selectedPlatforms.value = [];
    selectedStores.value = [];
    selectedDevelopers.value = [];
    selectedPublishers.value = [];
    selectedCreators.value = [];

    currentPage.value = 1;
  };

  const hasFilters = computed(
    () =>
      selectedGenres.value.length > 0 ||
      selectedTags.value.length > 0 ||
      selectedPlatforms.value.length > 0 ||
      selectedStores.value.length > 0 ||
      selectedDevelopers.value.length > 0 ||
      selectedPublishers.value.length > 0 ||
      selectedCreators.value.length > 0,
  );

  // Volver a la página inicial cuando se aplica un filtro
  watch(
    [
      genresQuery,
      tagsQuery,
      platformsQuery,
      storesQuery,
      developersQuery,
      publishersQuery,
      creatorsQuery,
      selectedOrder,
    ],
    () => {
      currentPage.value = 1;
    },
  );

  return {
    openDropdown,
    selectedOrder,

    selectedGenres,
    selectedTags,
    selectedPlatforms,
    selectedStores,
    selectedDevelopers,
    selectedPublishers,
    selectedCreators,

    genres,
    tags,
    platforms,
    stores,
    developers,
    publishers,
    creators,

    genresQuery,
    tagsQuery,
    platformsQuery,
    storesQuery,
    developersQuery,
    publishersQuery,
    creatorsQuery,

    clearFilters,
    hasFilters,
  };
};
