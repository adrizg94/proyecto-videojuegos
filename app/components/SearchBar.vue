<template>
  <div
    class="flex items-center rounded-4xl bg-white w-full py-3 px-4 gap-1 h-12 focus-within:ring-3 focus-within:ring-primary"
  >
    <FontAwesomeIcon
      icon="fa-magnifying-glass"
      class="text-primary scale-x-[-1] text-lg"
    />
    <input
      type="text"
      v-model="searchText"
      aria-label="Search"
      :placeholder="`Search ${gamesCount} games...`"
      class="flex-1 focus:outline-none"
    />
    <!-- placeholder="Find games, developers, authors and more..." -->
  </div>
</template>

<script setup>
const props = defineProps({
  gamesCount: String,
});

const searchText = defineModel({
  type: String,
  default: "",
});
</script>
