<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <PubliDevHero :name="publisher.name" />
      <PubliDevInfo :description="publisher.description" />
      <PubliDevGames
        :games="games"
        query-name="publishers"
        :id="publisher.id"
        :games-count-text="`${games.count} published games`"
      />
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const { data: publisher } = await useFetch(
  `/api/publishers/${route.params.id}`,
);
const { data: games } = await useFetch("/api/games", {
  query: {
    page: 1,
    page_size: 20,

    publishers: publisher.value.id,
  },
});
</script>
