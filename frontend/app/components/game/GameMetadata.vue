<!-- developers, publishers, genres, platforms, tags, esrb -->
<template>
  <section class="text-white grid grid-cols-2 gap-y-10 gap-x-8">
    <h2 class="sr-only">Game details</h2>
    <GameMetadataLinks
      name="Platforms"
      query-name="platforms"
      :links="game.platforms"
    />
    <GameMetadataLinks name="Genres" query-name="genres" :links="game.genres" />
    <GameMetadataLinks name="Tags" query-name="tags" :links="game.tags" />
    <GameMetadataLinks name="Stores" query-name="stores" :links="game.stores" />

    <!-- <p>Other games in the series</p> -->
  </section>
</template>

<script setup>
const props = defineProps({
  game: Object,
});
</script>
