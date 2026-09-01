<template>
  <header class="flex items-center gap-4 mb-5">
    <h1 class="font-bold text-4xl">
      {{ name }}
    </h1>
    <button type="button" class="cursor-pointer">
      <FontAwesomeIcon
        icon="fa-heart"
        class="text-4xl transition hover:text-primary-light hover:scale-110"
        aria-label="Add creator to favorites"
      />
    </button>
  </header>
</template>

<script setup>
const props = defineProps({
  name: String,
});
</script>
