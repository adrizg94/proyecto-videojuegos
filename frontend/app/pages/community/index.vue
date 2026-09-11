<template>
  <main class="w-full max-w-6xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <!-- Header -->
      <header class="mb-10">
        <div class="flex items-center gap-3">
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface text-primary"
          >
            <FontAwesomeIcon icon="fa-users" class="text-xl" />
          </div>

          <div>
            <h1 class="text-3xl font-bold">Community</h1>

            <p class="mt-1 text-text-muted">
              Join discussions, discover community lists and see what other
              players are talking about.
            </p>
          </div>
        </div>
      </header>

      <!-- Community sections -->
      <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <!-- General -->
        <CommunityCard
          title="General discussions"
          description="Talk about anything related to videogames."
          icon="fa-comments"
          to="/community/general"
          action="Browse discussions"
        />

        <!-- Games -->
        <CommunityCard
          title="Game discussions"
          description="Find a game and join its community discussions."
          icon="fa-gamepad"
        >
          <CommunityGameSearch />
        </CommunityCard>

        <!-- Lists -->
        <CommunityCard
          title="Community lists"
          description="Discover lists created by other users."
          icon="fa-list"
          to="/community/lists"
          action="Browse lists"
        />
      </section>

      <!-- Latest discussions -->
      <section class="mt-12">
        <div class="mb-5 flex flex-col">
          <h2 class="text-xl font-bold">Latest discussions</h2>

          <p class="mt-1 text-sm text-text-muted">
            See what the community is talking about.
          </p>
        </div>

        <CommunityThreadList
          :threads="latestThreads"
          empty-message="There are no discussions yet."
        />
      </section>
    </div>
  </main>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "Community",
  },
]);

/*
|--------------------------------------------------------------------------
| Composables
|--------------------------------------------------------------------------
*/

const { apiFetch } = useApi();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const pending = ref(true);
const latestThreads = ref([]);

/*
|--------------------------------------------------------------------------
| Community
|--------------------------------------------------------------------------
*/

const fetchCommunity = async () => {
  try {
    const response = await apiFetch("community");

    latestThreads.value = response.latest_threads ?? [];
  } catch (error) {
    console.error("Error loading community:", error);
  } finally {
    pending.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Page initialization
|--------------------------------------------------------------------------
*/

onMounted(fetchCommunity);
</script>
