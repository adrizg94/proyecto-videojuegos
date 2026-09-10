<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <!-- HEADER -->
      <section
        class="flex items-center justify-between rounded-2xl bg-surface p-6 mb-10"
      >
        <div>
          <p class="text-text-muted text-sm">My profile</p>

          <h1 class="text-3xl font-bold">
            {{ profile.user.username }}
          </h1>

          <p v-if="profile.user.created_at" class="text-text-muted mt-1">
            Member since {{ formatDate(profile.user.created_at) }}
          </p>
        </div>

        <NuxtLink
          to="/profile/settings"
          class="rounded-lg px-4 py-2 bg-hover hover:bg-primary transition-colors"
        >
          Settings
        </NuxtLink>
      </section>

      <!-- MY GAMES -->
      <section id="games" class="mb-12 scroll-mt-20">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-2xl font-bold">My games</h2>

          <span class="text-text-muted">
            {{ filteredGameStatuses.length }}
            {{ filteredGameStatuses.length === 1 ? "game" : "games" }}
          </span>
        </div>

        <!-- Filtros de estado -->
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
            @toggle-favorite="handleGameFavorite"
            :is-favorite="favoriteGameIds.includes(gameStatus.game.rawg_id)"
            @toggle-release-alert="handleReleaseAlert"
            :is-release-alert="
              releaseAlertIds.includes(gameStatus.game.rawg_id)
            "
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
            <FontAwesomeIcon
              icon="fa-bell"
              class="text-primary-light text-xl"
            />

            <h2 class="text-2xl font-bold">Release alerts</h2>
          </div>

          <span class="text-text-muted">
            {{ profile.release_alerts.length }}
            {{ profile.release_alerts.length === 1 ? "game" : "games" }}
          </span>
        </div>

        <div
          v-if="profile.release_alerts.length"
          class="flex justify-around flex-wrap gap-5"
        >
          <Card
            v-for="game in visibleReleaseAlerts"
            :key="game.id"
            :game="normalizeGame(game)"
            :link="`/games/${game.rawg_id}`"
            :is-favorite="favoriteGameIds.includes(game.rawg_id)"
            :is-release-alert="true"
            @toggle-favorite="handleGameFavorite"
            @toggle-release-alert="handleReleaseAlert"
          />
        </div>
        <p v-else class="text-text-muted">
          You don't have any release alerts yet.
        </p>

        <button
          v-if="profile.release_alerts.length > maxReleaseAlerts"
          type="button"
          class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
          @click="showAllReleaseAlerts = !showAllReleaseAlerts"
        >
          {{ showAllReleaseAlerts ? "Show less" : "Show more" }}
        </button>
      </section>

      <!-- FAVORITES -->
      <section id="favorites" class="mb-12 scroll-mt-20">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2 mb-5">
            <FontAwesomeIcon
              icon="fa-heart"
              class="text-primary-light text-xl"
            />
            <h2 class="text-2xl font-bold">Favorites</h2>
          </div>
          <span class="text-text-muted">
            {{ profile.favorite_games.length }}
            {{ profile.favorite_games.length === 1 ? "game" : "games" }}
          </span>
        </div>

        <!-- Juegos favoritos -->
        <div class="mb-12">
          <h3 class="text-lg font-semibold mb-4">Games</h3>

          <div
            v-if="profile.favorite_games.length"
            class="flex justify-around flex-wrap gap-5"
          >
            <Card
              v-for="game in visibleFavoriteGames"
              :key="game.id"
              :game="normalizeGame(game)"
              :link="`/games/${game.rawg_id}`"
              @toggle-favorite="handleGameFavorite"
              :is-favorite="true"
              @toggle-release-alert="handleReleaseAlert"
              :is-release-alert="releaseAlertIds.includes(game.rawg_id)"
            />
          </div>

          <p v-else class="text-text-muted">
            You don't have any favorite games yet.
          </p>
          <button
            v-if="profile.favorite_games.length > maxFavoriteGames"
            type="button"
            class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
            @click="showAllFavorites = !showAllFavorites"
          >
            {{ showAllFavorites ? "Show less" : "Show more" }}
          </button>
        </div>

        <!-- Entidades favoritas -->
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

      <!-- MY LISTS -->
      <section id="lists" class="mb-12 scroll-mt-20">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-2xl font-bold">My lists</h2>
          <span class="text-text-muted">
            {{ profile.game_lists.length }}
            {{ profile.game_lists.length === 1 ? "list" : "lists" }}
          </span>
        </div>

        <div
          v-if="profile.game_lists.length"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
        >
          <NuxtLink
            v-for="gameList in visibleLists"
            :key="gameList.id"
            :to="`/profile/lists/${gameList.id}`"
            class="rounded-xl bg-surface p-5 hover:bg-hover transition-colors"
          >
            <h3 class="font-semibold text-lg">
              {{ gameList.title }}
            </h3>

            <p class="text-text-muted text-sm mt-1">
              {{ gameList.games_count }}
              {{ gameList.games_count === 1 ? "game" : "games" }}
            </p>
          </NuxtLink>
        </div>

        <p v-else class="text-text-muted">You haven't created any lists yet.</p>
        <button
          v-if="profile.game_lists.length > maxLists"
          type="button"
          class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
          @click="showAllLists = !showAllLists"
        >
          {{ showAllLists ? "Show less" : "Show more" }}
        </button>
      </section>

      <!-- MY REVIEWS -->
      <section id="reviews" class="mb-12 scroll-mt-20">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-2xl font-bold">My reviews</h2>
          <span class="text-text-muted">
            {{ profile.reviews.length }}
            {{ profile.reviews.length === 1 ? "review" : "reviews" }}
          </span>
        </div>
        <div v-if="profile.reviews.length" class="flex flex-col gap-4">
          <NuxtLink
            v-for="review in visibleReviews"
            :key="review.id"
            :to="`/games/${review.game.rawg_id}`"
            class="rounded-xl bg-surface p-5 hover:bg-hover transition-colors"
          >
            <div class="flex items-center justify-between gap-5">
              <div>
                <h3 class="font-semibold text-lg">
                  {{ review.game.name }}
                </h3>

                <div class="flex gap-1 mt-1">
                  <FontAwesomeIcon
                    v-for="star in 5"
                    :key="star"
                    icon="fa-star"
                    :class="
                      star <= review.rating
                        ? 'text-primary-light'
                        : 'text-text-muted'
                    "
                  />
                </div>
              </div>

              <span class="text-text-muted text-sm">
                {{ formatDate(review.created_at) }}
              </span>
            </div>

            <p class="mt-4 text-text-muted line-clamp-3">
              {{ review.review }}
            </p>
          </NuxtLink>
        </div>

        <p v-else class="text-text-muted">
          You haven't published any reviews yet.
        </p>
        <button
          v-if="profile.reviews.length > maxReviews"
          type="button"
          class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
          @click="showAllReviews = !showAllReviews"
        >
          {{ showAllReviews ? "Show less" : "Show more" }}
        </button>
      </section>
      <Toast />
    </div>
  </main>
</template>

<script setup>
import UserHeader from "~/components/users/profile/UserHeader.vue";
import { useCreatorFavorites } from "~/composables/entities/useCreatorsFavorites";
import { useDeveloperFavorites } from "~/composables/entities/useDevelopersFavorites";
import { usePublisherFavorites } from "~/composables/entities/usePublishersFavorites";

const { apiFetch } = useApi();
const { user, fetchUser } = useAuth();
const { toggleFavorite } = useFavorites();
const { toggleReleaseAlert } = useReleaseAlerts();

const { removePublisher } = usePublisherFavorites();
const { removeDeveloper } = useDeveloperFavorites();
const { removeCreator } = useCreatorFavorites();

// --------------------------------
// PROFILE
// --------------------------------

const pending = ref(true);

const profile = ref({
  user: {},
  favorite_games: [],
  favorite_publishers: [],
  favorite_developers: [],
  favorite_creators: [],
  game_statuses: [],
  game_lists: [],
  reviews: [],
});

const fetchProfile = async () => {
  profile.value = await apiFetch("profile");
};

onMounted(async () => {
  try {
    // Al recargar, el estado de useAuth vuelve inicialmente a null
    if (!user.value) {
      await fetchUser();
    }

    // Si después de reconstruir la sesión sigue sin haber usuario
    if (!user.value) {
      await navigateTo("/login");
      return;
    }

    await fetchProfile();
  } catch (error) {
    console.error("Error loading profile:", error);
  } finally {
    pending.value = false;
  }
});

// --------------------------------
// GAME STATUS FILTERS
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
    return profile.value.game_statuses;
  }

  return profile.value.game_statuses.filter(
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
// Release Alerts
// --------------------------------
const showAllReleaseAlerts = ref(false);
const maxReleaseAlerts = 3;

const visibleReleaseAlerts = computed(() => {
  return showAllReleaseAlerts.value
    ? profile.value.release_alerts
    : profile.value.release_alerts.slice(0, maxReleaseAlerts);
});

const releaseAlertIds = computed(() => {
  return profile.value.release_alerts.map((game) => game.rawg_id);
});

const handleReleaseAlert = async (game) => {
  await toggleReleaseAlert(
    releaseAlertIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );

  await fetchProfile();
};

// --------------------------------
// FAVORITES
// --------------------------------
const showAllFavorites = ref(false);
const maxFavoriteGames = 3;

const visibleFavoriteGames = computed(() => {
  return showAllFavorites.value
    ? profile.value.favorite_games
    : profile.value.favorite_games.slice(0, maxFavoriteGames);
});

const favoriteGameIds = computed(() => {
  return profile.value.favorite_games.map((game) => game.rawg_id);
});

const maxEntities = 6;

const showAllEntities = reactive({
  Publishers: false,
  Developers: false,
  Creators: false,
});

const visibleEntities = (group) => {
  return showAllEntities[group.title]
    ? group.items
    : group.items.slice(0, maxEntities);
};

const toggleEntities = (group) => {
  showAllEntities[group.title] = !showAllEntities[group.title];
};

const removeEntityFavorite = async (group, entity) => {
  await group.removeFavorite(entity.name);

  await fetchProfile();
};

const favoriteEntityGroups = computed(() => [
  {
    title: "Publishers",
    items: profile.value.favorite_publishers,
    fallback: "/images/no-company.svg",
    removeFavorite: removePublisher,
  },
  {
    title: "Developers",
    items: profile.value.favorite_developers,
    fallback: "/images/no-company.svg",
    removeFavorite: removeDeveloper,
  },
  {
    title: "Creators",
    items: profile.value.favorite_creators,
    fallback: "/images/no-avatar.svg",
    removeFavorite: removeCreator,
  },
]);

const handleGameFavorite = async (game) => {
  await toggleFavorite(
    favoriteGameIds.value.includes(game.id),
    game.id,
    game.name,
    game.released,
    game.background_image,
  );

  await fetchProfile();
};

// --------------------------------
// My lists
// --------------------------------
const showAllLists = ref(false);
const maxLists = 3;

const visibleLists = computed(() => {
  return showAllLists.value
    ? profile.value.game_lists
    : profile.value.game_lists.slice(0, maxLists);
});

// --------------------------------
// Reviews
// --------------------------------
const showAllReviews = ref(false);
const maxReviews = 3;

const visibleReviews = computed(() => {
  return showAllReviews.value
    ? profile.value.reviews
    : profile.value.reviews.slice(0, maxReviews);
});

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

// --------------------------------
// DATE
// --------------------------------

const formatDate = (date) => {
  return new Intl.DateTimeFormat("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
  }).format(new Date(date));
};
</script>
