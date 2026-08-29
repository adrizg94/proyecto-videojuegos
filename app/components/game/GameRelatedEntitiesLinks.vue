<template>
  <section>
    <h3 class="p-1 text-center text-lg border-b-2 border-surface mb-1">
      {{ name }}
    </h3>
    <ul>
      <li
        v-for="link in visibleLinks"
        :key="link.id"
        class="flex justify-between items-center gap-2 py-2 px-2"
      >
        <div class="flex items-center gap-3">
          <img
            :src="
              link.image_background ||
              'https://placehold.co/128x128?text=No+Image'
            "
            :alt="link.name"
            class="size-16 shrink-0 rounded-full object-cover"
          />
          <NuxtLink to="/" class="hover:text-primary-light hover:underline">
            {{ link.name }}
          </NuxtLink>
        </div>
        <button
          type="button"
          class="bg-hover rounded-lg py-1 px-2 cursor-pointer hover:bg-primary-light transition-colors"
        >
          + Follow
        </button>
      </li>
    </ul>
    <button
      v-if="normalizedLinks.length > maxLinks"
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
  links: Object,
});

const normalizedLinks = computed(() => {
  return props.links.map((link) => {
    if (props.name === "Creators") {
      return {
        ...link,
        image_background: link.image,
      };
    }
    return link;
  });
});

const showAllLinks = ref(false);
const maxLinks = 3;

const visibleLinks = computed(() =>
  showAllLinks.value
    ? normalizedLinks.value
    : normalizedLinks.value.slice(0, maxLinks),
);
</script>
