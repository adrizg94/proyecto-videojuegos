<template>
  <!-- Perfil público de usuario -->
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <UserHeader :user="profile.user" />

      <UserFavorites
        :favorite-games="profile.favorite_games"
        :favorite-publishers="profile.favorite_publishers"
        :favorite-developers="profile.favorite_developers"
        :favorite-creators="profile.favorite_creators"
      />

      <UserLists
        title="Lists"
        :lists="profile.game_lists"
        :list-base-path="`/users/${profile.user.username}/lists`"
      />

      <UserReviews title="Reviews" :reviews="profile.reviews" />
    </div>
  </main>
</template>

<script setup>
import UserHeader from "~/components/users/profile/UserHeader.vue";
import UserFavorites from "~/components/users/profile/UserFavorites.vue";
import UserLists from "~/components/users/profile/UserLists.vue";
import UserReviews from "~/components/users/profile/UserReviews.vue";

/*
|--------------------------------------------------------------------------
| Route & composables
|--------------------------------------------------------------------------
*/

const route = useRoute();

const { apiFetch } = useApi();
const { setBreadcrumbs } = useBreadcrumbs();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const pending = ref(true);

const profile = ref({
  user: {},
  favorite_games: [],
  favorite_publishers: [],
  favorite_developers: [],
  favorite_creators: [],
  game_lists: [],
  reviews: [],
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

const fetchProfile = async () => {
  profile.value = await apiFetch(
    `users/${encodeURIComponent(route.params.username)}`,
  );

  setBreadcrumbs([
    {
      label: profile.value.user.username,
    },
  ]);
};

/*
|--------------------------------------------------------------------------
| Page initialization
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  try {
    await fetchProfile();
  } catch (error) {
    console.error("Error loading public profile:", error);

    if (error?.status === 404 || error?.statusCode === 404) {
      throw createError({
        statusCode: 404,
        statusMessage: "User not found",
      });
    }
  } finally {
    pending.value = false;
  }
});
</script>
