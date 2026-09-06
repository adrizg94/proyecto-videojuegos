export const useCatalog = (itemsCount, pageSize = 20) => {
  const currentPage = ref(1);
  const searchText = ref("");
  const searchQuery = ref("");

  const totalPages = computed(() => Math.ceil(itemsCount.value / pageSize));

  const changePage = (page) => {
    currentPage.value = page;
  };

  // Debounce para evitar que en la búsqueda se haga una petición a cada pulsación de tecla.
  watch(searchText, (newSearch, _, onCleanup) => {
    const timeout = setTimeout(() => {
      currentPage.value = 1;
      searchQuery.value = newSearch.trim();
    }, 800);

    onCleanup(() => clearTimeout(timeout));
  });

  return {
    currentPage,
    pageSize,
    searchText,
    searchQuery,
    totalPages,
    changePage,
  };
};
