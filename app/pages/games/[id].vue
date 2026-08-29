<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div
      class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.2fr)_minmax(450px,1fr)] gap-10"
    >
      <!-- Columna izquierda -->
      <div class="flex flex-col gap-2">
        <GameHero :game="game" />
        <GameInfo :description="englishDescription" />
        <GameMetadata :game="game" />
      </div>
      <!-- Columna derecha -->
      <div class="flex flex-col gap-2">
        <GameGallery :trailer="movies" :screenshots="screenshots" />
        <GameRelatedEntities :game="game" :creators="creators" />
      </div>
    </div>
  </div>
</template>
<script setup>
const route = useRoute();

const { data: game } = await useFetch(`/api/games/${route.params.id}`);

const { data: screenshots } = await useFetch(
  `/api/games/${route.params.id}/screenshots`,
  {
    transform: (data) => data.results,
  },
);

const { data: creators } = await useFetch(
  `/api/games/${route.params.id}/development-team`,
);

const { data: movies } = await useFetch("/api/steam/test-trailer");

const englishDescription = computed(() => {
  return game.value.description_raw?.split("Español")[0].trim();
});

// Función para recoger trailers de la API de rawg, la API a penas devuelve trailers, buscar otra fuente
// const { data: movies } = await useFetch(
//   `/api/games/${route.params.id}/movies`,
//   {
//     // transform: (data) => data.results[0].data["480"],
//     transform: (data) => data.results[0].data.max,
//   },
// );
</script>
