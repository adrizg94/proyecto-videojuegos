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
          class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"
        >
          <div class="flex items-center gap-3">
            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-surface text-primary"
            >
              <FontAwesomeIcon icon="fa-list" class="text-lg" />
            </div>

            <div>
              <h1 class="text-3xl font-bold">Community lists</h1>

              <p class="mt-1 text-text-muted">
                Discover game lists created by the community.
              </p>
            </div>
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
              placeholder="Search lists..."
              class="w-full rounded-lg border border-white/10 bg-surface py-2 pl-9 pr-3 text-sm outline-none transition-colors focus:border-primary"
            />
          </div>
        </div>
      </header>

      <!-- Count -->
      <section class="mb-5">
        <p class="text-sm text-text-muted">
          {{ filteredLists.length }}
          {{ filteredLists.length === 1 ? "list" : "lists" }}
        </p>
      </section>

      <!-- Lists -->
      <section
        v-if="filteredLists.length"
        class="grid gap-5 md:grid-cols-2 xl:grid-cols-3"
      >
        <CommunityListCard
          v-for="list in filteredLists"
          :key="list.id"
          :list="list"
        />
      </section>

      <!-- Empty search -->
      <section
        v-else-if="search"
        class="rounded-xl border border-white/10 bg-surface px-6 py-8 text-center"
      >
        <FontAwesomeIcon
          icon="fa-magnifying-glass"
          class="text-3xl text-text-muted"
        />

        <p class="mt-3 text-sm text-text-muted">No lists match your search.</p>
      </section>

      <!-- No lists -->
      <section
        v-else
        class="rounded-xl border border-white/10 bg-surface px-6 py-8 text-center"
      >
        <FontAwesomeIcon icon="fa-list" class="text-3xl text-text-muted" />

        <p class="mt-3 text-sm text-text-muted">
          There are no community lists yet.
        </p>
      </section>
    </div>
  </main>
</template>

<script setup>
const { apiFetch } = useApi();

const pending = ref(true);
const lists = ref([]);
const search = ref("");

const filteredLists = computed(() => {
  const query = search.value.toLowerCase();

  if (!query) {
    return lists.value;
  }

  return lists.value.filter((list) => {
    const title = list.title?.toLowerCase() ?? "";
    const username = list.user?.username?.toLowerCase() ?? "";

    return title.includes(query) || username.includes(query);
  });
});

const fetchLists = async () => {
  try {
    lists.value = await apiFetch("community/lists");
  } catch (error) {
    console.error("Error loading community lists:", error);
  } finally {
    pending.value = false;
  }
};

onMounted(fetchLists);
</script>
