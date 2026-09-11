<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <PubliDevHero
        :entity="developer"
        @toggle-favorite="handleDeveloperFavorite(developer)"
        :is-favorite="
          isAuthenticated && developerNames.includes(developer.name)
        "
      />

      <PubliDevInfo :description="developer.description" />

      <PubliDevGames
        :games="games"
        query-name="developers"
        :id="developer.id"
        :games-count-text="`${games.count} developed games`"
      />
    </div>

    <Toast />
  </div>
</template>

<script setup>
import { useDeveloperFavorites } from "~/composables/entities/useDevelopersFavorites";

/*
|--------------------------------------------------------------------------
| Route & composables
|--------------------------------------------------------------------------
*/

const route = useRoute();

const { isAuthenticated } = useAuth();
const { setBreadcrumbs } = useBreadcrumbs();

const { developerNames, toggleDeveloper } = useDeveloperFavorites();

/*
|--------------------------------------------------------------------------
| Developer
|--------------------------------------------------------------------------
*/

const { data: developer } = await useFetch(
  `/api/developers/${route.params.id}`,
);

setBreadcrumbs([
  {
    label: "Developers",
    to: "/developers",
  },
  {
    label: developer.value.name,
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

    developers: developer.value.id,
  },
});

/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

const handleDeveloperFavorite = (developer) => {
  toggleDeveloper(
    developerNames.value.includes(developer.name),
    developer.id,
    developer.name,
    developer.image_background,
  );
};
</script>
