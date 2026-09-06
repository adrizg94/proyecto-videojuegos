<template>
  <section>
    <h3 class="font-bold py-2">{{ name }}</h3>
    <ul class="flex flex-wrap gap-x-2 gap-y-2.5">
      <li v-for="link in visibleLinks" :key="link.id">
        <NuxtLink
          :to="{ path: '/games', query: { [queryName]: link.id } }"
          class="hover:text-primary-light underline transition"
        >
          {{ link.name }}
        </NuxtLink>
      </li>
    </ul>
    <button
      v-if="links.length > maxLinks"
      type="button"
      @click="showAllLinks = !showAllLinks"
      class="hover:bg-hover cursor-pointer rounded-sm mx-auto px-2 w-full my-2"
    >
      {{ showAllLinks ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  name: String,
  queryName: String,
  links: {
    type: Array,
    default: () => [],
  },
});

const showAllLinks = ref(false);
const maxLinks = 8;

const normalizedLinks = computed(() => {
  return props.links.map((link) => {
    if (props.name === "Platforms") return link.platform;
    if (props.name === "Stores") return link.store;

    return link;
  });
});

const visibleLinks = computed(() =>
  showAllLinks.value
    ? normalizedLinks.value
    : normalizedLinks.value.slice(0, maxLinks),
);
</script>
