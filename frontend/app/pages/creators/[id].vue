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

/*
|--------------------------------------------------------------------------
| Route & composables
|--------------------------------------------------------------------------
*/

const route = useRoute();

const { isAuthenticated } = useAuth();
const { setBreadcrumbs } = useBreadcrumbs();

const { creatorNames, toggleCreator } = useCreatorFavorites();

/*
|--------------------------------------------------------------------------
| Creator
|--------------------------------------------------------------------------
*/

const { data: creator } = await useFetch(`/api/creators/${route.params.id}`);

setBreadcrumbs([
  {
    label: "Creators",
    to: "/creators",
  },
  {
    label: creator.value.name,
  },
]);

/*
|--------------------------------------------------------------------------
| Games
|--------------------------------------------------------------------------
*/

const { data: games } = await useFetch("/api/games", {
  query: {
    page: 1,
    page_size: 20,

    creators: creator.value.id,
  },
});

/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

const handleCreatorFavorite = (creator) => {
  toggleCreator(
    creatorNames.value.includes(creator.name),
    creator.id,
    creator.name,
    creator.image,
  );
};
</script>
