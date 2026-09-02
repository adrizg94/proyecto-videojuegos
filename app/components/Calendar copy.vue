<template>
  <div
    class="absolute z-50 mt-2 w-72 rounded-lg bg-surface p-3"
  >
    <!-- Navegación mes -->
    <div class="grid grid-cols-[auto_1fr_auto] items-center mb-3">
      <button
        type="button"
        aria-label="Previous month"
        class="cursor-pointer hover:text-primary-light"
        @click="previousMonth"
      >
        <FontAwesomeIcon icon="fa-chevron-left" />
      </button>

      <span class="text-center font-semibold">
        {{ monthName }} {{ displayedYear }}
      </span>

      <button
        type="button"
        aria-label="Next month"
        class="cursor-pointer hover:text-primary-light"
        @click="nextMonth"
      >
        <FontAwesomeIcon icon="fa-chevron-right" />
      </button>
    </div>

    <!-- Días de la semana -->
    <div class="grid grid-cols-7 text-center text-sm text-text-muted mb-1">
      <span v-for="day in weekDays" :key="day">
        {{ day }}
      </span>
    </div>

    <!-- Días -->
    <div class="grid grid-cols-7 gap-1">
      <!-- Huecos antes del día 1 -->
      <div
        v-for="empty in emptyDays"
        :key="`empty-${empty}`"
      ></div>

      <button
        v-for="day in daysInMonth"
        :key="day"
        type="button"
        class="aspect-square rounded-md cursor-pointer
               hover:bg-hover transition-colors"
        :class="{
          'bg-primary text-white': isSelected(day),
        }"
        @click="selectDay(day)"
      >
        {{ day }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
  currentDate: {
    type: Date,
    required: true,
  },
});

const emit = defineEmits(["selectDate"]);

const displayedDate = ref(new Date(props.currentDate));

const weekDays = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];

const displayedYear = computed(() =>
  displayedDate.value.getFullYear(),
);

const displayedMonth = computed(() =>
  displayedDate.value.getMonth(),
);

const monthName = computed(() =>
  displayedDate.value.toLocaleString("en-US", {
    month: "long",
  }),
);

const daysInMonth = computed(() =>
  new Date(
    displayedYear.value,
    displayedMonth.value + 1,
    0,
  ).getDate(),
);

const emptyDays = computed(() => {
  const firstDay = new Date(
    displayedYear.value,
    displayedMonth.value,
    1,
  ).getDay();

  // JS: Sunday = 0
  // Calendario: Monday = 0
  return (firstDay + 6) % 7;
});

const previousMonth = () => {
  const date = new Date(displayedDate.value);

  date.setDate(1);
  date.setMonth(date.getMonth() - 1);

  displayedDate.value = date;
};

const nextMonth = () => {
  const date = new Date(displayedDate.value);

  date.setDate(1);
  date.setMonth(date.getMonth() + 1);

  displayedDate.value = date;
};

const selectDay = (day) => {
  const date = new Date(
    displayedYear.value,
    displayedMonth.value,
    day,
  );

  emit("selectDate", date);
};

const isSelected = (day) => {
  return (
    props.currentDate.getFullYear() === displayedYear.value &&
    props.currentDate.getMonth() === displayedMonth.value &&
    props.currentDate.getDate() === day
  );
};

watch(
  () => props.currentDate,
  (date) => {
    displayedDate.value = new Date(date);
  },
);
</script>