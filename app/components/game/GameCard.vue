<template>
  <NuxtLink :to="link" class="block">
    <article
      class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
    >
      <img
        :src="game.background_image || '/images/no-game.svg'"
        :alt="game.name"
        class="h-48 rounded-t-lg w-full"
        :class="
          game.background_image
            ? 'object-cover'
            : 'object-contain p-8'
        "
      />
      <h2
        class="flex flex-1 justify-center items-center font-bold text-white text-center"
      >
        {{ game.name }}
      </h2>
    </article>
  </NuxtLink>
</template>

<script setup>
const props = defineProps({
  game: Object,
  link: String,
});
</script>
