<template>
  <section class="flex flex-col gap-8">
    <GameRelatedEntitiesLinks
      name="Developers"
      :links="game.developers"
      :favorite-names="developerNames"
      @toggle-favorite="handleDeveloperFavorite"
    />
    <GameRelatedEntitiesLinks
      name="Publishers"
      :links="game.publishers"
      :favorite-names="publisherNames"
      @toggle-favorite="handlePublisherFavorite"
    />
    <GameRelatedEntitiesLinks
      name="Creators"
      :links="creators"
      :favorite-names="creatorNames"
      @toggle-favorite="handleCreatorFavorite"
    />
  </section>
</template>

<script setup>
import { useCreatorFavorites } from '~/composables/entities/useCreatorsFavorites';
import { useDeveloperFavorites } from '~/composables/entities/useDevelopersFavorites';
import { usePublisherFavorites } from '~/composables/entities/usePublishersFavorites';

const propts = defineProps({
  game: Object,
  creators: Object,
});

const { developerNames, toggleDeveloper } = useDeveloperFavorites();

const { publisherNames, togglePublisher } = usePublisherFavorites();

const { creatorNames, toggleCreator } = useCreatorFavorites();

const handleDeveloperFavorite = (developer) => {
  toggleDeveloper(
    developerNames.value.includes(developer.name),
    developer.name,
    developer.image_background,
  );
};

const handlePublisherFavorite = (publisher) => {
  togglePublisher(
    publisherNames.value.includes(publisher.name),
    publisher.name,
    publisher.image_background,
  );
};

const handleCreatorFavorite = (creator) => {
  toggleCreator(
    creatorNames.value.includes(creator.name),
    creator.name,
    creator.image_background,
  );
};
</script>
