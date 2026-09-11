<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else-if="data">
      <!-- Back -->
      <NuxtLink
        :to="backLink"
        class="mb-5 inline-flex items-center gap-2 text-sm text-text-muted transition-colors hover:text-white"
      >
        <FontAwesomeIcon icon="fa-arrow-left-long" class="text-xs" />

        {{ backText }}
      </NuxtLink>

      <!-- Header -->
      <header class="mb-8">
        <div
          class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
          <div class="flex items-start gap-3">
            <div
              class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-surface text-primary"
            >
              <FontAwesomeIcon icon="fa-list" />
            </div>

            <div>
              <h1 class="text-3xl font-bold">
                {{ data.list.title }}
              </h1>

              <div
                class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-text-muted"
              >
                <NuxtLink
                  :to="`/users/${data.user.username}`"
                  class="inline-flex items-center gap-2 transition-colors hover:text-white"
                >
                  <FontAwesomeIcon icon="fa-user" class="text-xs" />

                  {{ data.user.username }}
                </NuxtLink>

                <span class="inline-flex items-center gap-2">
                  <FontAwesomeIcon icon="fa-gamepad" class="text-xs" />

                  {{ data.list.games.length }}
                  {{ data.list.games.length === 1 ? "game" : "games" }}
                </span>

                <span
                  v-if="data.list.created_at"
                  class="inline-flex items-center gap-2"
                >
                  <FontAwesomeIcon icon="fa-calendar" class="text-xs" />

                  {{ formatDate(data.list.created_at) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Games -->
      <section>
        <div
          v-if="data.list.games.length"
          class="flex flex-wrap justify-around gap-5"
        >
          <Card
            v-for="game in data.list.games"
            :key="game.id"
            :game="normalizeGame(game)"
            :link="`/games/${game.rawg_id}`"
            :show-actions="false"
          />
        </div>

        <!-- Empty list -->
        <div
          v-else
          class="rounded-xl border border-white/10 bg-surface px-6 py-10 text-center"
        >
          <FontAwesomeIcon icon="fa-gamepad" class="text-3xl text-text-muted" />

          <p class="mt-3 text-sm text-text-muted">
            This list doesn't contain any games yet.
          </p>
        </div>
      </section>
    </div>

    <!-- Not found -->
    <div
      v-else
      class="rounded-xl border border-white/10 bg-surface px-6 py-10 text-center"
    >
      <FontAwesomeIcon icon="fa-list" class="text-3xl text-text-muted" />

      <h1 class="mt-4 text-xl font-bold">List not found</h1>

      <p class="mt-2 text-sm text-text-muted">
        This list doesn't exist or doesn't belong to this user.
      </p>

      <NuxtLink
        to="/community/lists"
        class="group mt-4 inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-white"
      >
        Back to community lists

        <FontAwesomeIcon
          icon="fa-arrow-right"
          class="text-xs transition-transform group-hover:translate-x-1"
        />
      </NuxtLink>
    </div>
  </main>
</template>

<script setup>
const route = useRoute();
const { apiFetch } = useApi();

const pending = ref(true);
const data = ref(null);

const backLink = computed(() => {
  if (route.query.from === "community-lists") {
    return "/community/lists";
  }

  if (route.query.from === "profile") {
    return "/profile";
  }

  return `/users/${route.params.username}`;
});

const backText = computed(() => {
  if (route.query.from === "community-lists") {
    return "Back to community lists";
  }

  if (route.query.from === "profile") {
    return "Back to my profile";
  }

  return `Back to ${data.value?.user.username ?? ""}'s profile`;
});

const normalizeGame = (game) => {
  return {
    id: game.rawg_id,
    name: game.name,
    released: game.release_date,
    background_image: game.image,
  };
};

const formatDate = (date) => {
  if (!date) return "";

  return new Date(date).toLocaleDateString("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });
};

const fetchList = async () => {
  try {
    data.value = await apiFetch(
      `users/${encodeURIComponent(route.params.username)}/lists/${route.params.id}`,
    );
  } catch (error) {
    console.error("Error loading public list:", error);

    data.value = null;
  } finally {
    pending.value = false;
  }
};

onMounted(fetchList);
</script>
