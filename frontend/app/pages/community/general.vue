<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <!-- Header -->
      <header class="mb-8">
        <NuxtLink
          to="/community"
          class="mb-5 inline-flex items-center gap-2 text-sm text-text-muted transition-colors hover:text-white"
        >
          <FontAwesomeIcon icon="fa-arrow-left-long" class="text-xs" />

          Back to community
        </NuxtLink>

        <div
          class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
        >
          <div>
            <div class="flex items-center gap-3">
              <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-surface text-primary"
              >
                <FontAwesomeIcon icon="fa-comments" class="text-lg" />
              </div>

              <div>
                <h1 class="text-3xl font-bold">General discussions</h1>

                <p class="mt-1 text-text-muted">
                  Talk about anything related to videogames.
                </p>
              </div>
            </div>
          </div>

          <!-- Authenticated -->
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

          <!-- Not authenticated -->
          <NuxtLink
            v-else
            to="/login"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80"
          >
            <FontAwesomeIcon icon="fa-right-to-bracket" />

            Log in to create thread
          </NuxtLink>
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
            Start a new topic for the community.
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

          <!-- Body -->
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
              : 'There are no general discussions yet.'
          "
          :show-empty-action="false"
        />
      </section>

      <Toast />
    </div>
  </main>
</template>

<script setup>
const { apiFetch } = useApi();
const { isAuthenticated } = useAuth();
const { showToast } = useToast();

const pending = ref(true);
const creating = ref(false);
const showCreateForm = ref(false);

const threads = ref([]);
const search = ref("");
const errors = ref({});

const form = reactive({
  title: "",
  body: "",
});

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

const fetchThreads = async () => {
  try {
    threads.value = await apiFetch("community/general");
  } catch (error) {
    console.error("Error loading general discussions:", error);
  }
};

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

const createThread = async () => {
  errors.value = {};
  creating.value = true;

  try {
    const response = await apiFetch("community/threads", {
      method: "POST",

      body: {
        title: form.title,
        body: form.body,
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

onMounted(async () => {
  await fetchThreads();
  pending.value = false;
});
</script>
