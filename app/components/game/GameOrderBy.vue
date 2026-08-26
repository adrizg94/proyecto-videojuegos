<template>
  <div class="relative w-fit cursor-pointer text-white">
    <button
      type="button"
      class="w-full text-left px-3 py-1 bg-surface hover:bg-hover rounded-lg cursor-pointer"
      :aria-expanded="openDropdown === dropdownName"
      @click="toggleDropdown(dropdownName)"
    >
      Order by: <span class="font-bold">{{ selectedName }}</span>
      <FontAwesomeIcon icon="fa-chevron-down" />
    </button>
    <div
      v-if="openDropdown === dropdownName"
      class="flex flex-col absolute z-20 bg-hover w-max mt-1 px-2 py-1 rounded-sm max-h-200 overflow-y-auto custom-scrollbar shadow-lg"
    >
      <button
        v-for="option in options"
        :key="option.value"
        type="button"
        class="text-text-muted hover:[text-shadow:0.3px_0_currentColor,-0.3px_0_currentColor] hover:text-white px-1 text-left"
        @click="updateSelected(option)"
      >
        {{ option.label }}
      </button>
    </div>
  </div>
</template>

<script setup>
const selected = defineModel("selected", {
  type: String,
  default: "",
});

const openDropdown = defineModel("openDropdown", {
  type: String,
  default: null,
});

const dropdownName = "order";

const options = [
  { label: "Relevance", value: "" },
  { label: "Release date", value: "-released" },
  { label: "Name", value: "name" },
  { label: "User rating", value: "-rating" },
  { label: "Metacritic", value: "-metacritic" },
];

const selectedName = computed(() => {
  return (
    options.find((option) => option.value === selected.value)?.label ??
    "Relevance"
  );
});

const updateSelected = (option) => {
  openDropdown.value = null;
  selected.value = option.value;
};

const toggleDropdown = (dropdown) => {
  openDropdown.value = openDropdown.value === dropdown ? null : dropdown;
};
</script>
