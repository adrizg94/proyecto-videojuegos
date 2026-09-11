<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <UserHeader :user="profile.user" is-private />

      <UserGameStatus
        :game-statuses="profile.game_statuses"
        :release-alerts="profile.release_alerts"
        :favorite-game-ids="favoriteGameIds"
        :release-alert-ids="releaseAlertIds"
        @toggle-favorite="handleGameFavorite"
        @toggle-release-alert="handleReleaseAlert"
      />

      <UserFavorites
        :favorite-games="profile.favorite_games"
        :favorite-publishers="profile.favorite_publishers"
        :favorite-developers="profile.favorite_developers"
        :favorite-creators="profile.favorite_creators"
        :release-alert-ids="releaseAlertIds"
        editable
        @toggle-favorite="handleGameFavorite"
        @toggle-release-alert="handleReleaseAlert"
        @remove-entity-favorite="handleRemoveEntityFavorite"
      />

      <UserLists
        :lists="profile.game_lists"
        :list-base-path="`/users/${profile.user.username}/lists`"
        from="profile"
      />

      <UserReviews :reviews="profile.reviews" />

      <Toast />
    </div>
  </main>
</template>

<script setup>
import UserHeader from "~/components/users/profile/UserHeader.vue";
import UserGameStatus from "~/components/users/profile/UserGameStatus.vue";
import UserFavorites from "~/components/users/profile/UserFavorites.vue";
import UserLists from "~/components/users/profile/UserLists.vue";
import UserReviews from "~/components/users/profile/UserReviews.vue";

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
  release_alerts: [],
  game_lists: [],
  reviews: [],
});

const fetchProfile = async () => {
  profile.value = await apiFetch("profile");
};

onMounted(async () => {
  try {
    if (!user.value) {
      await fetchUser();
    }

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
// IDS
// --------------------------------

const favoriteGameIds = computed(() => {
  return profile.value.favorite_games.map((game) => game.rawg_id);
});

const releaseAlertIds = computed(() => {
  return profile.value.release_alerts.map((game) => game.rawg_id);
});

// --------------------------------
// GAME FAVORITES
// --------------------------------

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
// RELEASE ALERTS
// --------------------------------

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
// ENTITY FAVORITES
// --------------------------------

const entityRemoveActions = {
  Publishers: removePublisher,
  Developers: removeDeveloper,
  Creators: removeCreator,
};

const handleRemoveEntityFavorite = async (groupTitle, entity) => {
  const removeFavorite = entityRemoveActions[groupTitle];

  if (!removeFavorite) return;

  await removeFavorite(entity.name);

  await fetchProfile();
};
</script>
