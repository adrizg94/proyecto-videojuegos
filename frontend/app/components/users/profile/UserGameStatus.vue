<template>
  <!-- MY GAMES -->
  <section id="games" class="mb-12 scroll-mt-20">
    <div class="flex items-center justify-between mb-5">
      <h2 class="text-2xl font-bold">My games</h2>

      <span class="text-text-muted">
        {{ filteredGameStatuses.length }}
        {{ filteredGameStatuses.length === 1 ? "game" : "games" }}
      </span>
    </div>

    <!-- Filtros -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        v-for="status in statusFilters"
        :key="status.value"
        type="button"
        class="rounded-lg px-3 py-1.5 cursor-pointer transition-colors"
        :class="
          selectedStatus === status.value
            ? 'bg-primary text-white'
            : 'bg-surface hover:bg-hover'
        "
        @click="selectedStatus = status.value"
      >
        {{ status.label }}
      </button>
    </div>

    <div
      v-if="filteredGameStatuses.length"
      class="flex justify-around flex-wrap gap-5"
    >
      <Card
        v-for="gameStatus in visibleStatusGames"
        :key="gameStatus.id"
        :game="normalizeGame(gameStatus.game)"
        :link="`/games/${gameStatus.game.rawg_id}`"
        :is-favorite="favoriteGameIds.includes(gameStatus.game.rawg_id)"
        :is-release-alert="releaseAlertIds.includes(gameStatus.game.rawg_id)"
        @toggle-favorite="handleFavorite"
        @toggle-release-alert="handleReleaseAlert"
      />
    </div>

    <p v-else class="text-text-muted">No games found.</p>

    <button
      v-if="filteredGameStatuses.length > maxStatusGames"
      type="button"
      class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
      @click="showAllStatusGames = !showAllStatusGames"
    >
      {{ showAllStatusGames ? "Show less" : "Show more" }}
    </button>
  </section>

  <!-- RELEASE ALERTS -->
  <section id="release-alerts" class="mb-12 scroll-mt-20">
    <div class="flex items-center justify-between mb-5">
      <div class="flex items-center gap-2">
        <FontAwesomeIcon icon="fa-bell" class="text-primary-light text-xl" />

        <h2 class="text-2xl font-bold">Release alerts</h2>
      </div>

      <span class="text-text-muted">
        {{ releaseAlerts.length }}
        {{ releaseAlerts.length === 1 ? "game" : "games" }}
      </span>
    </div>

    <div
      v-if="releaseAlerts.length"
      class="flex justify-around flex-wrap gap-5"
    >
      <Card
        v-for="game in visibleReleaseAlerts"
        :key="game.id"
        :game="normalizeGame(game)"
        :link="`/games/${game.rawg_id}`"
        :is-favorite="favoriteGameIds.includes(game.rawg_id)"
        :is-release-alert="true"
        @toggle-favorite="handleFavorite"
        @toggle-release-alert="handleReleaseAlert"
      />
    </div>

    <p v-else class="text-text-muted">You don't have any release alerts yet.</p>

    <button
      v-if="releaseAlerts.length > maxReleaseAlerts"
      type="button"
      class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
      @click="showAllReleaseAlerts = !showAllReleaseAlerts"
    >
      {{ showAllReleaseAlerts ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  gameStatuses: {
    type: Array,
    default: () => [],
  },

  releaseAlerts: {
    type: Array,
    default: () => [],
  },

  favoriteGameIds: {
    type: Array,
    default: () => [],
  },

  releaseAlertIds: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["toggleFavorite", "toggleReleaseAlert"]);

// --------------------------------
// STATUS
// --------------------------------

const selectedStatus = ref("all");

const statusFilters = [
  {
    label: "All",
    value: "all",
  },
  {
    label: "Playing",
    value: "playing",
  },
  {
    label: "Backlog",
    value: "backlog",
  },
  {
    label: "Completed",
    value: "completed",
  },
  {
    label: "Paused",
    value: "paused",
  },
  {
    label: "Dropped",
    value: "dropped",
  },
];

const filteredGameStatuses = computed(() => {
  if (selectedStatus.value === "all") {
    return props.gameStatuses;
  }

  return props.gameStatuses.filter(
    (gameStatus) => gameStatus.status === selectedStatus.value,
  );
});

const showAllStatusGames = ref(false);
const maxStatusGames = 6;

const visibleStatusGames = computed(() => {
  return showAllStatusGames.value
    ? filteredGameStatuses.value
    : filteredGameStatuses.value.slice(0, maxStatusGames);
});

// --------------------------------
// RELEASE ALERTS
// --------------------------------

const showAllReleaseAlerts = ref(false);
const maxReleaseAlerts = 3;

const visibleReleaseAlerts = computed(() => {
  return showAllReleaseAlerts.value
    ? props.releaseAlerts
    : props.releaseAlerts.slice(0, maxReleaseAlerts);
});

// --------------------------------
// EVENTS
// --------------------------------

const handleFavorite = (game) => {
  emit("toggleFavorite", game);
};

const handleReleaseAlert = (game) => {
  emit("toggleReleaseAlert", game);
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
