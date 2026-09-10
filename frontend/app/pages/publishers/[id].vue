<template>
  <div class="w-full max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col gap-2">
      <PubliDevHero
        :entity="publisher"
        @toggle-favorite="handlePublisherFavorite(publisher)"
        :is-favorite="
          isAuthenticated && publisherNames.includes(publisher.name)
        "
      />
      <PubliDevInfo :description="publisher.description" />
      <PubliDevGames
        :games="games"
        query-name="publishers"
        :id="publisher.id"
        :games-count-text="`${games.count} published games`"
      />
    </div>
    <Toast />
  </div>
</template>

<script setup>
import { usePublisherFavorites } from "~/composables/entities/usePublishersFavorites";

const route = useRoute();

const { isAuthenticated } = useAuth();

const { publisherNames, togglePublisher } = usePublisherFavorites();

const handlePublisherFavorite = (publisher) => {
  togglePublisher(
    publisherNames.value.includes(publisher.name),
    publisher.id,
    publisher.name,
    publisher.image_background,
  );
};

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
