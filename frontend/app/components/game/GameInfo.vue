<!-- Descripción, duración, información general -->
<template>
  <section class="flex flex-col gap-2 text-white pb-10">
    <h2 class="font-bold text-2xl">About</h2>
    <p>{{ visibleDescription }}</p>
    <button
      class="hover:bg-hover cursor-pointer rounded-sm"
      v-if="description.length > maxCharDescription"
      type="button"
      @click="showFullDescription = !showFullDescription"
    >
      {{ showFullDescription ? "Show less" : "Show more" }}
    </button>
    <GameSteamInfo
      v-if="steamAppId"
      :details="details"
      :steam-app-id="steamAppId"
      :players="players"
    />
  </section>
</template>

<script setup>
const props = defineProps({
  description: {
    type: String,
    default: "",
  },
  isHtml: {
    type: Boolean,
    default: false,
  },
  details: {
    type: Object,
    default: null,
  },
  steamAppId: {
    type: String,
    default: null,
  },
  players: {
    type: Number,
    default: null,
  },
});

const showFullDescription = ref(false);

const maxCharDescription = 500;

const visibleDescription = computed(() => {
  return props.description.length < maxCharDescription ||
    showFullDescription.value
    ? props.description
    : props.description.slice(0, maxCharDescription) + "...";
});
</script>

<!-- Versión con composable useShowMore -->
<!-- <template>
  <section class="flex flex-col gap-2 text-white pb-10">
    <h1 class="font-bold text-2xl">About</h1>
    <p>{{ visibleContent }}</p>
    <button
    class="hover:bg-hover cursor-pointer rounded-sm"
    v-if="hasMore"
    type="button"
    @click="toggleShowMore"
    >
    {{ showAll ? "Show less" : "Show more" }}
  </button>
</section>
</template>

<script setup>
const props = defineProps({
  description: String,
});

const maxCharDescription = 500;
const descriptionContent = computed(() => props.description);

const { showAll, hasMore, visibleContent, toggleShowMore } = useShowMore(
  descriptionContent,
  maxCharDescription,
  );
</script> -->
