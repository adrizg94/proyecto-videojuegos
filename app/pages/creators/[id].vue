<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <CreatorHero :creator="creator" />
      <PubliDevInfo :description="creator.description" />
      <PubliDevGames :games="games" query-name="creators" :id="creator.id" :games-count-text="`Credited on ${games.count} games`" />
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const { data: creator } = await useFetch(`/api/creators/${route.params.id}`);
const { data: games } = await useFetch("/api/games", {
  query: {
    page: 1,
    page_size: 20,

    creators: creator.value.id,
  },
});
</script>
