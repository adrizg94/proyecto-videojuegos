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

const route = useRoute();

const { isAuthenticated } = useAuth();

const { developerNames, toggleDeveloper } = useDeveloperFavorites();

const handleDeveloperFavorite = (developer) => {
  toggleDeveloper(
    developerNames.value.includes(developer.name),
    developer.name,
    developer.image_background,
  );
};

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
