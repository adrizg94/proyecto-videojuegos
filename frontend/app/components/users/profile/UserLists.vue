<template>
  <section id="lists" class="mb-12 scroll-mt-20">
    <div class="flex items-center justify-between mb-5">
      <h2 class="text-2xl font-bold">
        {{ title }}
      </h2>

      <span class="text-text-muted">
        {{ lists.length }}
        {{ lists.length === 1 ? "list" : "lists" }}
      </span>
    </div>

    <div
      v-if="lists.length"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
    >
      <NuxtLink
        v-for="gameList in visibleLists"
        :key="gameList.id"
        :to="{
          path: `${listBasePath}/${gameList.id}`,
          query: from ? { from } : {},
        }"
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

    <p v-else class="text-text-muted">No lists yet.</p>

    <button
      v-if="lists.length > maxLists"
      type="button"
      class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
      @click="showAllLists = !showAllLists"
    >
      {{ showAllLists ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  lists: {
    type: Array,
    default: () => [],
  },

  title: {
    type: String,
    default: "My lists",
  },

  listBasePath: {
    type: String,
    required: true,
  },
  from: {
    type: String,
    default: null,
  },
});

const showAllLists = ref(false);
const maxLists = 3;

const visibleLists = computed(() => {
  return showAllLists.value ? props.lists : props.lists.slice(0, maxLists);
});
</script>
