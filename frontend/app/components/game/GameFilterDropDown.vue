<template>
  <div class="relative w-fit">
    <button
      type="button"
      class="w-full text-left px-3 py-1 bg-surface hover:bg-hover rounded-lg cursor-pointer"
      :aria-expanded="openDropdown === dropdownName"
      @click="toggleDropdown"
    >
      {{ label }}
      <FontAwesomeIcon icon="fa-chevron-down" />
    </button>
    <fieldset
      v-if="openDropdown === dropdownName"
      class="flex flex-col absolute z-20 bg-hover w-max mt-1 px-2 py-1 rounded-sm max-h-200 overflow-y-auto custom-scrollbar shadow-lg"
    >
      <legend class="sr-only">{{ label }}</legend>
      <label v-for="option in options" :key="option.id">
        <input
          v-model="selected"
          type="checkbox"
          :value="option.id"
          class="peer accent-primary"
        />
        <span
          class="text-text-muted hover:text-white hover:[text-shadow:0.3px_0_currentColor,-0.3px_0_currentColor] peer-checked:text-white peer-checked:font-bold px-1"
          >{{ option.name }}</span
        >
      </label>
    </fieldset>
  </div>
</template>

<script setup>
const props = defineProps({
  label: String,
  options: Array,
  dropdownName: String,
});
const selected = defineModel("selected", {
  type: Array,
  default: () => [],
});

const openDropdown = defineModel("openDropdown", {
  type: String,
  default: null,
});

const toggleDropdown = () => {
  openDropdown.value =
    openDropdown.value === props.dropdownName ? null : props.dropdownName;
};
</script>
