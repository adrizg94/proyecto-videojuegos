<!-- Portada, nombre, fecha de lanzamiento, valoración, platadormas -->

<template>
  <header class="flex flex-col gap-3 pt-8 pb-5">
    <div class="flex items-center gap-5">
      <p class="bg-white text-black rounded-sm px-2 w-fit">
        {{ game.tba ? "TBA" : game.released }}
      </p>
      <div class="flex items-center gap-2">
        <FontAwesomeIcon v-if="hasPC" :icon="['fab', 'windows']" />
        <FontAwesomeIcon v-if="hasPlaystation" :icon="['fab', 'playstation']" />
        <FontAwesomeIcon v-if="hasXbox" :icon="['fab', 'xbox']" />
      </div>
      <img
        v-if="pegiIcons[details?.ratings?.pegi?.rating]"
        :src="pegiIcons[details?.ratings?.pegi?.rating]"
        :alt="`PEGI ${details?.ratings?.pegi?.rating}`"
        :title="`PEGI ${details?.ratings?.pegi?.rating}`"
        class="h-9"
      />
    </div>
    <div class="flex items-center gap-4">
      <h1 class="font-bold text-4xl">{{ game.name }}</h1>
      <button
        v-if="isAuthenticated"
        type="button"
        @click="handleFavorite"
        class="cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-heart"
          class="text-4xl transition hover:text-primary-light hover:scale-110"
          :class="{ 'text-primary-light': isFavorite }"
          aria-label="Add game to favorites"
        />
      </button>
      <button
        v-if="isAuthenticated && showReleaseAlert"
        type="button"
        @click="handleReleaseAlert"
        class="cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-bell"
          class="text-4xl transition hover:text-primary-light hover:scale-110"
          :class="{ 'text-primary-light': isReleaseAlert }"
          aria-label="Add game to favorites"
        />
      </button>
    </div>
    <div v-if="isAuthenticated" class="relative">
      <button
        @click="showMyGames = !showMyGames"
        class="group flex items-center gap-1 w-fit mb-5 text-lg hover:bg-hover px-2 py-1 rounded-lg cursor-pointer transition-colors"
        :class="{ 'bg-hover hover:bg-surface': showMyGames }"
      >
        My games
        <FontAwesomeIcon
          :icon="showMyGames ? 'fa-chevron-up' : 'fa-chevron-down'"
          class="group-hover:text-primary-light transition-colors"
        />
      </button>
      <div
        v-if="showMyGames"
        class="absolute left-0 top-10 z-50 mt-2 w-50 rounded-2xl bg-surface p-5 shadow-lg"
      >
        <GameStatusUser :game="game" />

        <div class="my-5 w-20 border-t border-text-muted/50" />

        <GameListUser :game="game" @create-list="showCreateList = true" />
      </div>
    </div>
    <div v-if="game.ratings_count" class="text-xl flex gap-3 items-center">
      <span class="font-bold"
        ><FontAwesomeIcon icon="fa-star" /> {{ game.rating }}</span
      >
      <span class="font-semibold">{{ ratingValue }}</span>
      <a
        v-if="details?.metacritic?.url"
        :href="details?.metacritic?.url"
        class="group flex items-center gap-1 ml-4"
      >
        <span class="group-hover:text-primary-light transition-colors"
          >Metacritic</span
        >
        <span class="rounded px-1.5 py-0.5 bg-surface/70 font-semibold">{{
          details?.metacritic?.score
        }}</span></a
      >
    </div>
  </header>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import GameStatusUser from "./users/GameStatusUser.vue";
import GameListUser from "./users/GameListUser.vue";

const props = defineProps({
  game: Object,
  details: {
    type: Object,
    default: null,
  },
  isAuthenticated: {
    type: Boolean,
    default: false,
  },
});

const pegiIcons = {
  3: "/images/pegi/3.svg",
  7: "/images/pegi/7.svg",
  12: "/images/pegi/12.svg",
  16: "/images/pegi/16.svg",
  18: "/images/pegi/18.svg",
};

const showMyGames = ref(false);

const { favoriteIds, toggleFavorite } = useFavorites();
const { releaseAlertIds, toggleReleaseAlert } = useReleaseAlerts();

const handleFavorite = () => {
  toggleFavorite(
    isFavorite.value,
    props.game?.id,
    props.game?.name,
    props.game?.released,
    props.game?.background_image,
  );
};
const isFavorite = computed(() => favoriteIds.value.includes(props.game?.id));

const handleReleaseAlert = () => {
  toggleReleaseAlert(
    isReleaseAlert.value,
    props.game?.id,
    props.game?.name,
    props.game?.released,
    props.game?.background_image,
  );
};
const isReleaseAlert = computed(() =>
  releaseAlertIds.value.includes(props.game?.id),
);

const showReleaseAlert = computed(() => {
  if (!props.game) return false;

  // Fecha todavía por determinar
  if (props.game.tba) return true;

  // No tenemos fecha de lanzamiento
  if (!props.game.released) return true;

  const [year, month, day] = props.game.released.split("-").map(Number);

  const releaseDate = new Date(year, month - 1, day);

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  // Campana solamente si todavía no ha salido
  return releaseDate > today;
});

const ratingValue = computed(() => {
  if (props.game.rating >= 4.5) return "Exceptional";
  if (props.game.rating >= 4) return "Excellent";
  if (props.game.rating >= 3) return "Good";
  if (props.game.rating >= 2) return "Average";
  return "Poor";
});

const hasPC = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 1),
);

const hasPlaystation = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 2),
);

const hasXbox = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 3),
);
</script>
