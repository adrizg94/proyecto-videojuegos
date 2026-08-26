<!-- screenshots, imágenes, trailers -->
<template>
  <section class="grid grid-cols-2 py-8 gap-4">
    <img
      v-for="(screenshot, index) in screenshots.slice(0, 3)"
      :key="screenshot.id"
      :src="screenshot.image"
      :alt="`Screenshot ${screenshot.id}`"
      :class="
        index === 0
          ? 'col-span-2 w-full h-80 object-cover rounded-lg'
          : 'w-full h-40 object-cover rounded-lg'
      "
    />
  </section>
</template>

<script setup>
const props = defineProps({
  screenshots: Array,
});
</script>
