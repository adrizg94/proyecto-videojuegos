<template>
  <div class="relative overflow-hidden">
    <div
      v-if="details?.background_raw || details?.background"
      class="absolute top-0 left-0 w-full pointer-events-none"
    >
      <img
        :src="details?.background_raw || details?.background"
        class="w-full h-auto opacity-20"
      />
      <div
        class="absolute inset-x-0 bottom-0 h-80 bg-linear-to-b via-transparent to-background"
      ></div>
    </div>
    <!-- class="absolute inset-0 h-full w-full object-cover opacity-40" -->
    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 py-8">
      <div
        class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.2fr)_minmax(450px,1fr)] gap-10"
      >
        <!-- Columna izquierda -->
        <div class="flex flex-col gap-2">
          <GameHero :game="game" :details="details" />
          <GameInfo
            :description="englishDescription"
            :details="details"
            :steam-app-id="steamAppId"
            :players="players"
          />
          <GameMetadata :game="game" />
          <GameSteamPress
            v-if="details?.reviews"
            :reviews="details.reviews"
            class="mt-8"
          />
          <!-- <GameSteamAchievements
            v-if="details?.achievements"
            :achievements="details.achievements"
            class="mt-8"
          /> -->
          <GameSteamReviews v-if="reviews" :reviews="reviews" class="mt-8" />
        </div>
        <!-- Columna derecha -->
        <div class="flex flex-col gap-2">
          <GameGallery
            v-if="details"
            :trailer="movies"
            :screenshots="screenshots"
          />
          <GameRelatedEntities :game="game" :creators="creators" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
const route = useRoute();

const { data: game } = await useFetch(`/api/games/${route.params.id}`);

// const { data: screenshots } = await useFetch(
//   `/api/games/${route.params.id}/screenshots`,
//   {
//     transform: (data) => data.results,
//   },
// );

const { data: creators } = await useFetch(
  `/api/games/${route.params.id}/development-team`,
);

// const { data: movies } = await useFetch("/api/steam/test-trailer");

const { data: stores } = await useFetch(
  `/api/games/${route.params.id}/stores`,
  {
    transform: (data) => data.results ?? [],
  },
);

const steamAppId = computed(() => {
  const steam = stores.value.find((store) =>
    store.url.includes("store.steampowered.com"),
  );

  if (!steam) return null;

  return steam.url.split("/app/")[1].split("/")[0];
});

const details = ref(null);
const players = ref(null);
const reviews = ref(null);

// Solo pedir datos de Steam si el juego está en Steam
if (steamAppId.value) {
  const { data: detailsData } = await useFetch(
    `/api/steam/details/${steamAppId.value}`,
  );
  details.value = detailsData.value;

  const { data: playersData } = await useFetch(
    `/api/steam/players/${steamAppId.value}`,
    {
      transform: (data) => data.response.player_count ?? null,
    },
  );
  players.value = playersData.value;

  const { data: reviewsData } = await useFetch(
    `/api/steam/reviews/${steamAppId.value}`,
    {
      transform: (data) => data.reviews ?? null,
    },
  );
  reviews.value = reviewsData.value;
}

const movies = computed(() => details.value.movies[0]) ?? [];

const screenshots = computed(() => details.value.screenshots) ?? null;

const englishDescription = computed(() => {
  return game.value.description_raw?.split("Español")[0].trim() ?? "";
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
