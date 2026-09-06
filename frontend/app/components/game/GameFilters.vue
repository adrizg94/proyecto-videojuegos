<template>
  <section class="flex gap-2 text-white" aria-label="Game filters">
    <GameFilterDropDown
      v-model:selected="selectedGenres"
      v-model:open-dropdown="openDropdown"
      dropdown-name="genres"
      label="Genres"
      :options="optionsGenres"
    />

    <GameFilterDropDown
      v-model:selected="selectedTags"
      v-model:open-dropdown="openDropdown"
      dropdown-name="tags"
      label="Tags"
      :options="optionsTags"
    />

    <GameFilterDropDown
      v-model:selected="selectedPlatforms"
      v-model:open-dropdown="openDropdown"
      dropdown-name="platforms"
      label="Platforms"
      :options="optionsPlatforms"
    />

    <GameFilterDropDown
      v-model:selected="selectedStores"
      v-model:open-dropdown="openDropdown"
      dropdown-name="stores"
      label="Stores"
      :options="optionsStores"
    />

    <GameFilterDropDown
      v-model:selected="selectedDevelopers"
      v-model:open-dropdown="openDropdown"
      dropdown-name="developers"
      label="Developed by"
      :options="optionsDevelopers"
    />

    <GameFilterDropDown
      v-model:selected="selectedPublishers"
      v-model:open-dropdown="openDropdown"
      dropdown-name="publishers"
      label="Published by"
      :options="optionsPublishers"
    />
  </section>
</template>

<script setup>
const props = defineProps({
  optionsGenres: Array,
  optionsTags: Array,
  optionsPlatforms: Array,
  optionsStores: Array,
  optionsDevelopers: Array,
  optionsPublishers: Array,
});

const selectedGenres = defineModel("genres", {
  type: Array,
  default: () => [],
});

const selectedTags = defineModel("tags", {
  type: Array,
  default: () => [],
});

const selectedPlatforms = defineModel("platforms", {
  type: Array,
  default: () => [],
});

const selectedStores = defineModel("stores", {
  type: Array,
  default: () => [],
});

const selectedDevelopers = defineModel("developers", {
  type: Array,
  default: () => [],
});

const selectedPublishers = defineModel("publishers", {
  type: Array,
  default: () => [],
});

const openDropdown = defineModel("openDropdown", {
  type: String,
  default: null,
});
</script>
