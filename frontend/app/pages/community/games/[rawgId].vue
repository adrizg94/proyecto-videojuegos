<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else-if="game">
      <!-- Back -->
      <NuxtLink
        to="/community"
        class="mb-5 inline-flex items-center gap-2 text-sm text-text-muted transition-colors hover:text-white"
      >
        <FontAwesomeIcon icon="fa-arrow-left-long" class="text-xs" />

        Back to community
      </NuxtLink>

      <!-- Game header -->
      <header class="mb-8 rounded-xl border border-white/10 bg-surface p-5">
        <div
          class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
        >
          <div class="flex min-w-0 items-center gap-4">
            <img
              :src="game.background_image ?? '/images/image-not-found.svg'"
              :alt="game.name"
              class="h-24 w-36 shrink-0 rounded-lg object-cover"
            />

            <div class="min-w-0">
              <div class="mb-2 flex items-center gap-2 text-sm text-primary">
                <FontAwesomeIcon icon="fa-gamepad" />

                Game discussions
              </div>

              <h1 class="text-3xl font-bold">
                {{ game.name }}
              </h1>

              <p class="mt-1 text-sm text-text-muted">
                Discuss {{ game.name }} with other players.
              </p>
            </div>
          </div>

          <div class="flex shrink-0 flex-wrap items-center gap-3">
            <!-- Game page -->
            <NuxtLink
              :to="`/games/${game.id}`"
              class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-text-muted transition-colors hover:bg-hover hover:text-white"
            >
              <FontAwesomeIcon icon="fa-arrow-up-right-from-square" />

              View game
            </NuxtLink>

            <!-- Create thread -->
            <button
              v-if="isAuthenticated"
              type="button"
              class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 cursor-pointer"
              @click="toggleCreateForm"
            >
              <FontAwesomeIcon
                :icon="showCreateForm ? 'fa-xmark' : 'fa-plus'"
              />

              {{ showCreateForm ? "Cancel" : "Create thread" }}
            </button>

            <NuxtLink
              v-else
              to="/login"
              class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80"
            >
              <FontAwesomeIcon icon="fa-right-to-bracket" />

              Log in to create thread
            </NuxtLink>
          </div>
        </div>
      </header>

      <!-- Create thread -->
      <section
        v-if="showCreateForm && isAuthenticated"
        class="mb-8 rounded-xl border border-white/10 bg-surface p-6"
      >
        <div class="mb-5">
          <h2 class="text-xl font-bold">Create a discussion</h2>

          <p class="mt-1 text-sm text-text-muted">
            Start a new discussion about {{ game.name }}.
          </p>
        </div>

        <form class="space-y-5" @submit.prevent="createThread">
          <!-- Title -->
          <div>
            <label for="thread-title" class="mb-2 block text-sm font-medium">
              Title
            </label>

            <input
              id="thread-title"
              v-model.trim="form.title"
              type="text"
              maxlength="150"
              placeholder="What do you want to talk about?"
              class="w-full rounded-lg border border-white/10 bg-background px-4 py-3 text-sm outline-none transition-colors focus:border-primary"
            />

            <p v-if="errors.title" class="mt-2 text-sm text-red-400">
              {{ errors.title[0] }}
            </p>
          </div>

          <!-- Message -->
          <div>
            <div class="mb-2 flex items-center justify-between gap-3">
              <label for="thread-body" class="text-sm font-medium">
                Message
              </label>

              <span class="text-xs text-text-muted">
                {{ form.body.length }}/5000
              </span>
            </div>

            <textarea
              id="thread-body"
              v-model.trim="form.body"
              maxlength="5000"
              rows="7"
              placeholder="Write your message..."
              class="w-full resize-y rounded-lg border border-white/10 bg-background px-4 py-3 text-sm outline-none transition-colors focus:border-primary"
            ></textarea>

            <p v-if="errors.body" class="mt-2 text-sm text-red-400">
              {{ errors.body[0] }}
            </p>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3">
            <button
              type="button"
              class="rounded-lg px-4 py-2 text-sm font-medium text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
              @click="cancelCreate"
            >
              Cancel
            </button>

            <button
              type="submit"
              :disabled="creating"
              class="inline-flex min-w-32 items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
            >
              <FontAwesomeIcon
                v-if="creating"
                icon="fa-spinner"
                class="animate-spin"
              />

              <FontAwesomeIcon v-else icon="fa-paper-plane" />

              {{ creating ? "Publishing..." : "Publish" }}
            </button>
          </div>
        </form>
      </section>

      <!-- Discussions -->
      <section>
        <div
          class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
        >
          <div>
            <h2 class="text-xl font-bold">Discussions</h2>

            <p class="mt-1 text-sm text-text-muted">
              {{ threads.length }}
              {{ threads.length === 1 ? "discussion" : "discussions" }}
            </p>
          </div>

          <!-- Search -->
          <div class="relative w-full sm:w-72">
            <FontAwesomeIcon
              icon="fa-magnifying-glass"
              class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-text-muted"
            />

            <input
              v-model.trim="search"
              type="text"
              placeholder="Search discussions..."
              class="w-full rounded-lg border border-white/10 bg-surface py-2 pl-9 pr-3 text-sm outline-none transition-colors focus:border-primary"
            />
          </div>
        </div>

        <CommunityThreadList
          :threads="filteredThreads"
          :empty-message="
            search
              ? 'No discussions match your search.'
              : `There are no discussions about ${game.name} yet.`
          "
          :show-empty-action="false"
        />
      </section>

      <Toast />
    </div>

    <!-- Game not found -->
    <div
      v-else
      class="rounded-xl border border-white/10 bg-surface px-6 py-10 text-center"
    >
      <FontAwesomeIcon icon="fa-gamepad" class="text-3xl text-text-muted" />

      <p class="mt-3 text-text-muted">Game not found.</p>

      <NuxtLink
        to="/community"
        class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-white"
      >
        Back to community

        <FontAwesomeIcon icon="fa-arrow-right" class="text-xs" />
      </NuxtLink>
    </div>
  </main>
</template>

<script setup>
const route = useRoute();

const { apiFetch } = useApi();

const { user, isAuthenticated, fetchUser } = useAuth();

const { showToast } = useToast();

const rawgId = route.params.rawgId;

const pending = ref(true);
const creating = ref(false);
const showCreateForm = ref(false);

const game = ref(null);
const threads = ref([]);

const search = ref("");
const errors = ref({});

const form = reactive({
  title: "",
  body: "",
});

/*
|--------------------------------------------------------------------------
| FILTER THREADS
|--------------------------------------------------------------------------
*/

const filteredThreads = computed(() => {
  const query = search.value.toLowerCase();

  if (!query) {
    return threads.value;
  }

  return threads.value.filter((thread) => {
    const title = thread.title?.toLowerCase() ?? "";

    const username = thread.user?.username?.toLowerCase() ?? "";

    return title.includes(query) || username.includes(query);
  });
});

/*
|--------------------------------------------------------------------------
| FETCH GAME
|--------------------------------------------------------------------------
*/

const fetchGame = async () => {
  try {
    game.value = await $fetch(`/api/games/${rawgId}`);
  } catch (error) {
    console.error("Error loading RAWG game:", error);

    game.value = null;
  }
};

/*
|--------------------------------------------------------------------------
| FETCH THREADS
|--------------------------------------------------------------------------
*/

const fetchThreads = async () => {
  try {
    threads.value = await apiFetch(`community/games/${rawgId}`);
  } catch (error) {
    console.error("Error loading game discussions:", error);

    threads.value = [];
  }
};

/*
|--------------------------------------------------------------------------
| CREATE FORM
|--------------------------------------------------------------------------
*/

const toggleCreateForm = () => {
  showCreateForm.value = !showCreateForm.value;

  if (!showCreateForm.value) {
    resetForm();
  }
};

const resetForm = () => {
  form.title = "";
  form.body = "";

  errors.value = {};
};

const cancelCreate = () => {
  resetForm();

  showCreateForm.value = false;
};

/*
|--------------------------------------------------------------------------
| CREATE THREAD
|--------------------------------------------------------------------------
*/

const createThread = async () => {
  errors.value = {};
  creating.value = true;

  try {
    const response = await apiFetch("community/threads", {
      method: "POST",

      body: {
        title: form.title,
        body: form.body,

        rawg_id: game.value.id,
        name: game.value.name,

        release_date: game.value.released ?? null,

        image: game.value.background_image ?? null,
      },
    });

    showToast(response.message);

    resetForm();

    showCreateForm.value = false;

    await fetchThreads();
  } catch (error) {
    errors.value = error.data?.errors ?? {};

    if (!error.data?.errors) {
      showToast(
        `ERROR: ${
          error.data?.message ?? error.message ?? "Error creating thread"
        }`,
      );
    }
  } finally {
    creating.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| LOAD PAGE
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  try {
    if (!user.value) {
      await fetchUser();
    }

    await Promise.all([fetchGame(), fetchThreads()]);
  } finally {
    pending.value = false;
  }
});
</script>
