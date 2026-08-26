<!-- Portada, nombre, fecha de lanzamiento, valoración, platadormas -->

<template>
  <section class="flex flex-col justify-start gap-2 py-8">
    <p class="bg-white rounded-sm px-2 w-fit">{{ date }}</p>
    <h1 class="font-bold text-4xl text-white">{{ name }}</h1>
    <p class="text-white">{{ rating }}</p>
  </section>
</template>

<script setup>
const props = defineProps({
  date: Date,
  name: String,
  rating: Number,
  background_image: String,
});
</script>
