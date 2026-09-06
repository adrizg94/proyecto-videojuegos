<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <PubliDevHero :name="developer.name" />
      <PubliDevInfo :description="developer.description" />
      <PubliDevGames
        :games="games"
        query-name="developers"
        :id="developer.id"
        :games-count-text="`${games.count} developed games`"
      />
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const { data: developer } = await useFetch(
  `/api/developers/${route.params.id}`,
);
const { data: games } = await useFetch("/api/games", {
  query: {
    page: 1,
    page_size: 20,

    developers: developer.value.id,
  },
});
</script>
