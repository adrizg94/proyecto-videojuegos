<!-- <template>
  <article
    class="flex-1 grid grid-cols-[1fr_2fr_1fr] items-center px-2 max-w-1/2 h-10 border-b-2 border-hover"
  >
    <div class="flex gap-4">
      <div>
        {{ viewDate }}
        <FontAwesomeIcon icon="fa-calendar-day" />
      </div>
      <button><FontAwesomeIcon icon="fa-crosshairs" /></button>
    </div>
    <div class="flex justify-center">
      <div v-for="(value, index) in period">
        {{ value }}
        <span v-if="index < period.length - 1" class="mx-2">|</span>
      </div>
    </div>
    <div class="flex justify-end items-center gap-2">
      Show filters<FontAwesomeIcon icon="fa-filter" />
    </div>
  </article>
</template> -->

<template>
  <section
    aria-label="Release navigation"
    class="flex flex-col mx-auto w-4/9 m-6"
  >
    <div
      class="grid grid-cols-[1.2fr_2fr_1fr] items-center px-2 pb-1 border-b-3 border-surface"
    >
      <!-- Fecha -->
      <div class="flex gap-3 mr-3">
        <button
          type="button"
          aria-label="Select date"
          class="flex items-center gap-1 px-2 py-1 rounded-md cursor-pointer hover:bg-hover/40 hover:text-primary-light transition whitespace-nowrap"
        >
          <span class="text-accent font-semibold text-lg">{{ viewDate }}</span>
          <FontAwesomeIcon icon="fa-calendar-day" />
        </button>
        <button
          type="button"
          aria-label="Go to today"
          title="Go to today"
          @click="emit('goToToday')"
          class="cursor-pointer hover:text-primary-light transition"
        >
          <FontAwesomeIcon icon="fa-crosshairs" />
        </button>
      </div>

      <!-- Periodos -->
      <div class="flex justify-center">
        <template v-for="(periodName, index) in period" :key="periodName">
          <button
            type="button"
            :aria-pressed="selectPeriod === periodName"
            @click="changePeriod(periodName)"
            class="cursor-pointer hover:text-primary-light transition"
            :class="{ 'text-primary-light': selectPeriod === periodName }"
          >
            {{ periodName }}
          </button>
          <span
            v-if="index < period.length - 1"
            class="mx-2 cursor-default"
            aria-hidden="true"
            >|</span
          >
        </template>
      </div>

      <!-- Filtros -->
      <button
        type="button"
        class="flex justify-self-end items-center gap-2 cursor-pointer hover:text-primary-light transition ml-3"
        :aria-expanded="showFilters"
        aria-controls="release-filters"
        @click="showFilters = !showFilters"
      >
        {{ showFilters ? "Hide Filters" : "Show Filters" }}
        <FontAwesomeIcon icon="fa-filter" />
      </button>
    </div>
    <GameFilters
      v-if="showFilters"
      id="release-filters"
      v-model:genres="selectedGenres"
      v-model:tags="selectedTags"
      v-model:platforms="selectedPlatforms"
      v-model:stores="selectedStores"
      v-model:developers="selectedDevelopers"
      v-model:publishers="selectedPublishers"
      v-model:open-dropdown="openDropdown"
      :options-genres="genres"
      :options-tags="tags"
      :options-platforms="platforms"
      :options-stores="stores"
      :options-developers="developers"
      :options-publishers="publishers"
      class="flex justify-center items-center h-10 pb-1 bg-surface rounded-b-lg"
    />
    <button
      v-if="hasFilters"
      type="button"
      class="self-end cursor-pointer rounded-lg w-fit mr-3 my-2 px-2 py-1 text-text-muted hover:bg-hover hover:text-white"
      @click="clearFilters"
    >
      <FontAwesomeIcon icon="fa-filter-circle-xmark" />
      Clear Filters
    </button>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const showFilters = ref(false);

const props = defineProps({
  currentDate: Date,
  selectPeriod: String,
  genres: Array,
  tags: Array,
  platforms: Array,
  stores: Array,
  developers: Array,
  publishers: Array,
  clearFilters: Object,
  hasFilters: Object,
});

const emit = defineEmits(["changePeriod", "goToToday"]);

const selectedGenres = defineModel("selectedGenres", {
  type: Array,
  default: () => [],
});

const selectedTags = defineModel("selectedTags", {
  type: Array,
  default: () => [],
});

const selectedPlatforms = defineModel("selectedPlatforms", {
  type: Array,
  default: () => [],
});

const selectedStores = defineModel("selectedStores", {
  type: Array,
  default: () => [],
});

const selectedDevelopers = defineModel("selectedDevelopers", {
  type: Array,
  default: () => [],
});

const selectedPublishers = defineModel("selectedPublishers", {
  type: Array,
  default: () => [],
});

const openDropdown = defineModel("openDropdown", {
  default: null,
});

const viewDate = computed(() => {
  const date = new Date(props.currentDate);

  if (props.selectPeriod === "Daily") {
    const month = date.toLocaleString("en-US", {
      month: "long",
    });
    return `${date.getDate()} ${month}`;
  }

  // Calcular día/mes inicial/final para el periodo semanal
  if (props.selectPeriod === "Weekly") {
    const endWeek = new Date(date);
    endWeek.setDate(endWeek.getDate() + 6);

    const startMonth = date.toLocaleString("en-US", {
      month: "short",
    });

    const endMonth = endWeek.toLocaleString("en-US", {
      month: "short",
    });

    if (date.getMonth() === endWeek.getMonth()) {
      return `${startMonth} ${date.getDate()} – ${endWeek.getDate()}`;
    }

    return `${startMonth} ${date.getDate()} – ${endMonth} ${endWeek.getDate()}`;
  }

  if (props.selectPeriod === "Monthly") {
    const currentYear = new Date().getFullYear();

    return date.toLocaleString("en-US", {
      month: "long",
      ...(date.getFullYear() !== currentYear && {
        year: "numeric",
      }),
    });
  }

  if (props.selectPeriod === "Yearly") {
    return String(date.getFullYear());
  }

  if (props.selectPeriod === "Upcoming") {
    return "";
  }

  return "";
});

const period = ["Daily", "Weekly", "Monthly", "Yearly", "Upcoming/TBA"];

const changePeriod = (periodName) => {
  emit("changePeriod", periodName);
};
</script>
