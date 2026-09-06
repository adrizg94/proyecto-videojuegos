<template>
  <div
    class="absolute top-full left-0 z-50 mt-3 w-72 p-3 bg-surface border border-primary-light/30 rounded-lg shadow-2xl"
  >
    <!-- Cabecera -->
    <div class="grid grid-cols-[auto_1fr_auto] items-center mb-3">
      <!-- Anterior -->
      <button
        type="button"
        :aria-label="viewMode === 'days' ? 'Previous month' : 'Previous years'"
        class="cursor-pointer hover:text-primary-light transition-colors"
        @click="previous"
      >
        <FontAwesomeIcon icon="fa-chevron-left" />
      </button>

      <!-- Mes / año -->
      <div
        v-if="viewMode === 'days'"
        class="flex justify-center items-center gap-1 font-semibold"
      >
        <span>
          {{ monthName }}
        </span>

        <button
          type="button"
          aria-label="Select year"
          class="cursor-pointer rounded px-1 hover:text-primary-light hover:bg-hover transition-colors"
          @click="openYearView"
        >
          {{ displayedYear }}
        </button>
      </div>

      <!-- Rango de años -->
      <div v-else class="text-center font-semibold">
        {{ yearRangeStart }} – {{ yearRangeStart + 11 }}
      </div>

      <!-- Siguiente -->
      <button
        type="button"
        :aria-label="viewMode === 'days' ? 'Next month' : 'Next years'"
        class="cursor-pointer hover:text-primary-light transition-colors"
        @click="next"
      >
        <FontAwesomeIcon icon="fa-chevron-right" />
      </button>
    </div>

    <!-- ========================= -->
    <!-- Vista de días -->
    <!-- ========================= -->

    <template v-if="viewMode === 'days'">
      <!-- Días de la semana -->
      <div class="grid grid-cols-7 text-center text-sm text-text-muted mb-1">
        <span v-for="day in weekDays" :key="day">
          {{ day }}
        </span>
      </div>

      <!-- Días del mes -->
      <div class="grid grid-cols-7 gap-1">
        <!-- Espacios anteriores al día 1 -->
        <div v-for="empty in emptyDays" :key="`empty-${empty}`"></div>

        <!-- Días -->
        <button
          v-for="day in daysInMonth"
          :key="day"
          type="button"
          :aria-pressed="isSelected(day)"
          class="aspect-square rounded-md cursor-pointer hover:bg-hover transition-colors"
          :class="{
            'bg-primary': isSelected(day),
          }"
          @click="selectDay(day)"
        >
          {{ day }}
        </button>
      </div>
    </template>

    <!-- ========================= -->
    <!-- Vista de años -->
    <!-- ========================= -->

    <div v-else class="grid grid-cols-3 gap-2">
      <button
        v-for="year in years"
        :key="year"
        type="button"
        :aria-pressed="year === displayedYear"
        class="py-2 rounded-md cursor-pointer hover:bg-hover transition-colors"
        :class="{
          'bg-primary': year === displayedYear,
        }"
        @click="selectYear(year)"
      >
        {{ year }}
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

// Fecha que está mostrando el calendario.
// No tiene por qué ser todavía la fecha seleccionada.
const displayedDate = ref(new Date(props.currentDate));

// "days" | "years"
const viewMode = ref("days");

// Primer año del bloque de 12 años
const yearRangeStart = ref(
  Math.floor(displayedDate.value.getFullYear() / 10) * 10,
);

const weekDays = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];

const displayedYear = computed(() => {
  return displayedDate.value.getFullYear();
});

const displayedMonth = computed(() => {
  return displayedDate.value.getMonth();
});

const monthName = computed(() => {
  return displayedDate.value.toLocaleString("en-US", {
    month: "long",
  });
});

// Número de días del mes actual
const daysInMonth = computed(() => {
  return new Date(displayedYear.value, displayedMonth.value + 1, 0).getDate();
});

// Huecos antes del día 1.
// JS empieza la semana en domingo:
// domingo = 0
//
// Lo transformamos para:
// lunes = 0
const emptyDays = computed(() => {
  const firstDay = new Date(
    displayedYear.value,
    displayedMonth.value,
    1,
  ).getDay();

  return (firstDay + 6) % 7;
});

// Los 12 años que aparecen en la vista de años
const years = computed(() => {
  return Array.from({ length: 12 }, (_, index) => yearRangeStart.value + index);
});

const previous = () => {
  if (viewMode.value === "years") {
    yearRangeStart.value -= 12;
    return;
  }

  const date = new Date(displayedDate.value);

  // Evitamos problemas como 31 de agosto → septiembre
  date.setDate(1);
  date.setMonth(date.getMonth() - 1);

  displayedDate.value = date;
};

const next = () => {
  if (viewMode.value === "years") {
    yearRangeStart.value += 12;
    return;
  }

  const date = new Date(displayedDate.value);

  date.setDate(1);
  date.setMonth(date.getMonth() + 1);

  displayedDate.value = date;
};

const openYearView = () => {
  // Por ejemplo:
  // 2026 → bloque 2020 – 2031
  yearRangeStart.value = Math.floor(displayedYear.value / 10) * 10;

  viewMode.value = "years";
};

const selectYear = (year) => {
  const date = new Date(displayedDate.value);

  // Normalizamos antes de cambiar el año
  date.setDate(1);
  date.setFullYear(year);

  displayedDate.value = date;

  // Volvemos automáticamente al calendario
  viewMode.value = "days";
};

const selectDay = (day) => {
  const date = new Date(displayedYear.value, displayedMonth.value, day);

  emit("selectDate", date);
};

const isSelected = (day) => {
  return (
    props.currentDate.getFullYear() === displayedYear.value &&
    props.currentDate.getMonth() === displayedMonth.value &&
    props.currentDate.getDate() === day
  );
};

// Si currentDate cambia desde fuera:
// flechas de Releases, Go Today, etc.
// sincronizamos también el calendario.
watch(
  () => props.currentDate,
  (date) => {
    displayedDate.value = new Date(date);
  },
);
</script>
