<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div
      class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(320px,420px)] gap-10"
    >
      <!-- Columna izquierda -->
      <div class="flex flex-col gap-2">
        <GameHero
          :date="game.released"
          :name="game.name"
          :rating="game.rating"
          :background_image="game.background_image"
        />
        <GameInfo :description="englishDescription" />
      </div>
      <!-- Columna derecha -->
      <div class="flex flex-col gap-2">
        <GameGallery :screenshots="screenshots" />
      </div>
    </div>

    <!-- <div v-if="game">
    {{ game.name }}
    <br />
    {{ game.description }}
    <br />
    {{ game.released }}
    <br />
    {{ game.rating }}
    <br />
    {{ game.playtime }}
    <br />
    <br />
    <br />
    {{ game.platforms }}
    <br />
    <br />
    <br />
    {{ game.stores }}
    <br />
    <br />
    <br />
    {{ game.developers }}
    <br />
    <br />
    <br />
    {{ game.genres }}
    <br />
    <br />
    <br />
    {{ game.tags }}
    <br />
    <br />
    <br />
    {{ game.publishers }}
    <br />
    <br />
    <br />
    {{ game.esrb_rating }}
  </div> -->
  </div>
</template>
<script setup>
// const props = defineProps({
//     id: String,
//     name: String,
//     date: Date,
//     rating: Number,
//     platform: Array, // name: PC, PlayStation 5, PlayStation 4, PlayStation 3, Xbox Series S/X, Xbox One, Xbox 360
//     genres: Array,
//     stores: Array,
//     tags: Array,
//     age_raging: Object,
//     screenshots: Array,
// })

const route = useRoute();

const { data: game } = await useFetch(`/api/games/${route.params.id}`);

const { data: screenshots } = await useFetch(
  `/api/games/${route.params.id}/screenshots`,
  {
    transform: (data) => data.results,
  },
);

const englishDescription = computed(() => {
  return game.value.description_raw?.split("Español")[0].trim();
});
</script>
