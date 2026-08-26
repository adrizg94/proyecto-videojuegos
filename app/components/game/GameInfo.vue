<!-- Descripción, duración, información general -->
<template>
  <section class="flex flex-col gap-2 text-white">
    <h1 class="font-bold text-2xl">About</h1>
    <p>{{ description }}</p>
  </section>
</template>

<script setup>
const props = defineProps({
  description: String,
});
</script>
