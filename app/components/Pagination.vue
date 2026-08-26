<template>
  <nav class="flex justify-center gap-2">
    <button
      type="button"
      aria-label="Previous page"
      class="btn-number disabled:opacity-40"
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
    >
      <FontAwesomeIcon icon="fa-chevron-left" />
    </button>
    <button
      type="button"
      :aria-label="`Button ${page}`"
      :disabled="currentPage === page"
      class="btn-number disabled:bg-primary"
      v-for="page in visiblePages"
      :key="page"
      @click="changePage(page)"
    >
      {{ page }}
    </button>
    <button
      type="button"
      aria-label="Next page"
      class="btn-number disabled:opacity-40"
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
    >
      <FontAwesomeIcon icon="fa-chevron-right" />
    </button>
  </nav>
</template>

<script setup>
const props = defineProps({
  currentPage: Number,
  totalPages: Number,
});
const emit = defineEmits(["changePage"]);

const visiblePages = computed(() => {
  let endPage = Math.min(props.totalPages, Math.max(5, props.currentPage + 2));
  let startPage = Math.max(1, endPage - 4);

  return Array.from(
    { length: endPage - startPage + 1 },
    (_, index) => startPage + index,
  );
});

const changePage = (page) => {
  emit("changePage", page);
};
</script>
