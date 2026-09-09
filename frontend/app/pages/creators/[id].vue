<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <CreatorHero
        :creator="creator"
        @toggle-favorite="handleCreatorFavorite(creator)"
        :is-favorite="isAuthenticated && creatorNames.includes(creator.name)"
      />
      <PubliDevInfo :description="creator.description" />
      <PubliDevGames
        :games="games"
        query-name="creators"
        :id="creator.id"
        :games-count-text="`Credited on ${games.count} games`"
      />
    </div>
    <Toast />
  </div>
</template>

<script setup>
import { useCreatorFavorites } from "~/composables/entities/useCreatorsFavorites";

const route = useRoute();

const { isAuthenticated } = useAuth();

const { creatorNames, toggleCreator } = useCreatorFavorites();

const handleCreatorFavorite = (creator) => {
  toggleCreator(
    creatorNames.value.includes(creator.name),
    creator.name,
    creator.image_background,
  );
};

const { data: creator } = await useFetch(`/api/creators/${route.params.id}`);
const { data: games } = await useFetch("/api/games", {
  query: {
    page: 1,
    page_size: 20,

    creators: creator.value.id,
  },
});
</script>
