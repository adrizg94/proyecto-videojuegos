<template>
  <section id="favorites" class="mb-12 scroll-mt-20">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 mb-5">
        <FontAwesomeIcon icon="fa-heart" class="text-primary-light text-xl" />

        <h2 class="text-2xl font-bold">Favorites</h2>
      </div>

      <span class="text-text-muted">
        {{ favoriteGames.length }}
        {{ favoriteGames.length === 1 ? "game" : "games" }}
      </span>
    </div>

    <!-- GAMES -->
    <div class="mb-12">
      <h3 class="text-lg font-semibold mb-4">Games</h3>

      <div
        v-if="favoriteGames.length"
        class="flex justify-around flex-wrap gap-5"
      >
        <Card
          v-for="game in visibleFavoriteGames"
          :key="game.id"
          :game="normalizeGame(game)"
          :link="`/games/${game.rawg_id}`"
          :is-favorite="true"
          :is-release-alert="releaseAlertIds.includes(game.rawg_id)"
          :show-actions="editable"
          @toggle-favorite="handleFavorite"
          @toggle-release-alert="handleReleaseAlert"
        />
      </div>

      <p v-else class="text-text-muted">No favorite games yet.</p>

      <button
        v-if="favoriteGames.length > maxFavoriteGames"
        type="button"
        class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
        @click="showAllFavorites = !showAllFavorites"
      >
        {{ showAllFavorites ? "Show less" : "Show more" }}
      </button>
    </div>

    <!-- ENTITIES -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <article
        v-for="group in favoriteEntityGroups"
        :key="group.title"
        class="rounded-xl bg-surface p-5"
      >
        <div class="flex items-center justify-between mb-6">
          <h3 class="font-semibold text-lg">
            {{ group.title }}
          </h3>

          <span class="text-sm text-text-muted">
            {{ group.items.length }}
          </span>
        </div>

        <div v-if="group.items.length" class="flex flex-col gap-3">
          <div
            v-for="entity in visibleEntities(group)"
            :key="entity.id"
            class="flex items-center rounded-lg hover:bg-hover transition-colors"
          >
            <NuxtLink
              :to="`/${group.title.toLowerCase()}/${entity.rawg_id}`"
              class="flex flex-1 min-w-0 items-center gap-3 p-2"
            >
              <img
                :src="entity.image || group.fallback"
                :alt="entity.name"
                class="size-10 rounded-full object-cover shrink-0"
              />

              <span class="flex-1 truncate">
                {{ entity.name }}
              </span>
            </NuxtLink>

            <button
              v-if="editable"
              type="button"
              title="Remove from favorites"
              class="cursor-pointer p-3"
              @click="removeEntityFavorite(group, entity)"
            >
              <FontAwesomeIcon
                icon="fa-heart"
                class="text-xl text-primary-light transition hover:scale-110"
              />
            </button>
          </div>

          <button
            v-if="group.items.length > maxEntities"
            type="button"
            class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
            @click="toggleEntities(group)"
          >
            {{ showAllEntities[group.title] ? "Show less" : "Show more" }}
          </button>
        </div>

        <p v-else class="text-text-muted text-sm">No favorites yet.</p>
      </article>
    </div>
  </section>
</template>

<script setup>
const props = defineProps({
  favoriteGames: {
    type: Array,
    default: () => [],
  },

  favoritePublishers: {
    type: Array,
    default: () => [],
  },

  favoriteDevelopers: {
    type: Array,
    default: () => [],
  },

  favoriteCreators: {
    type: Array,
    default: () => [],
  },

  releaseAlertIds: {
    type: Array,
    default: () => [],
  },

  editable: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits([
  "toggleFavorite",
  "toggleReleaseAlert",
  "removeEntityFavorite",
]);

// --------------------------------
// FAVORITE GAMES
// --------------------------------

const showAllFavorites = ref(false);
const maxFavoriteGames = 3;

const visibleFavoriteGames = computed(() => {
  return showAllFavorites.value
    ? props.favoriteGames
    : props.favoriteGames.slice(0, maxFavoriteGames);
});

// --------------------------------
// FAVORITE ENTITIES
// --------------------------------

const maxEntities = 6;

const showAllEntities = reactive({
  Publishers: false,
  Developers: false,
  Creators: false,
});

const favoriteEntityGroups = computed(() => [
  {
    title: "Publishers",
    items: props.favoritePublishers,
    fallback: "/images/no-company.svg",
  },
  {
    title: "Developers",
    items: props.favoriteDevelopers,
    fallback: "/images/no-company.svg",
  },
  {
    title: "Creators",
    items: props.favoriteCreators,
    fallback: "/images/no-avatar.svg",
  },
]);

const visibleEntities = (group) => {
  return showAllEntities[group.title]
    ? group.items
    : group.items.slice(0, maxEntities);
};

const toggleEntities = (group) => {
  showAllEntities[group.title] = !showAllEntities[group.title];
};

// --------------------------------
// EVENTS
// --------------------------------

const handleFavorite = (game) => {
  emit("toggleFavorite", game);
};

const handleReleaseAlert = (game) => {
  emit("toggleReleaseAlert", game);
};

const removeEntityFavorite = (group, entity) => {
  emit("removeEntityFavorite", group.title, entity);
};

// --------------------------------
// GAME ADAPTER
// --------------------------------

const normalizeGame = (game) => {
  return {
    id: game.rawg_id,
    name: game.name,
    released: game.release_date,
    background_image: game.image,
  };
};
</script>
