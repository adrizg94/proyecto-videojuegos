<template>
  <NuxtLink :to="link" class="block">
    <article
      class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
    >
      <img
        :src="image ?? 'https://placehold.co/600x400?text=Image+Not+Found'"
        :alt="name"
        class="h-48 object-cover rounded-t-lg w-full"
      />
      <h2
        class="flex flex-1 justify-center items-center font-bold text-white text-center"
      >
        {{ name }}
      </h2>
    </article>
  </NuxtLink>
</template>

<script setup>
const props = defineProps({
  image: String,
  name: String,
  link: String,
});
</script>
