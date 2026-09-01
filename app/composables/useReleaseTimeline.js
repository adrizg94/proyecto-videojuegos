export const useReleaseTimeline = () => {
  // Abstraer variables reactivas de las que depende el fetch
  // en un solo objeto para no provocar multiples peticiones
  const timeline = ref({
    selectedPeriod: "Weekly",
    currentDates: {
      Daily: new Date(),
      Weekly: new Date(),
      Monthly: new Date(),
      Yearly: new Date(),
    },
  });

  const selectedPeriod = computed(() => timeline.value.selectedPeriod);
  const currentDate = computed(
    () => timeline.value.currentDates[timeline.value.selectedPeriod],
  );

  const formattedDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
  };

  // Calcular rango de fechas
  const dateRange = computed(() => {
    const date = new Date(currentDate.value);
    const period = timeline.value.selectedPeriod;

    if (period === "Daily") {
      return {
        start: formattedDate(date),
        end: formattedDate(date),
      };
    }

    if (period === "Weekly") {
      const startDate = new Date(date);

      // Lunes como primer día de la semana
      const daysFromMonday = (startDate.getDay() + 6) % 7;
      startDate.setDate(startDate.getDate() - daysFromMonday);

      const endDate = new Date(startDate);
      endDate.setDate(endDate.getDate() + 6);

      return {
        start: formattedDate(startDate),
        end: formattedDate(endDate),
      };
    }

    if (period === "Monthly") {
      const startDate = new Date(date.getFullYear(), date.getMonth(), 1);

      const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);

      return {
        start: formattedDate(startDate),
        end: formattedDate(endDate),
      };
    }

    if (period === "Yearly") {
      const startDate = new Date(date.getFullYear(), 0, 1);

      const endDate = new Date(date.getFullYear(), 11, 31);

      return {
        start: formattedDate(startDate),
        end: formattedDate(endDate),
      };
    }

    return {
      start: "",
      end: "",
    };
  });

  const changePeriod = (periodName) => {
    timeline.value.selectedPeriod = periodName;
  };

  const nextPeriod = () => {
    const period = timeline.value.selectedPeriod;
    const date = new Date(timeline.value.currentDates[period]);

    if (period === "Daily") {
      date.setDate(date.getDate() + 1);
    }
    if (period === "Weekly") {
      date.setDate(date.getDate() + 7);
    }
    if (period === "Monthly") {
      date.setDate(1);
      date.setMonth(date.getMonth() + 1);
    }
    if (period === "Yearly") {
      date.setMonth(0, 1);
      date.setFullYear(date.getFullYear() + 1);
    }
    timeline.value.currentDates[period] = date;
  };

  const previousPeriod = () => {
    const period = timeline.value.selectedPeriod;
    const date = new Date(timeline.value.currentDates[period]);

    if (period === "Daily") {
      date.setDate(date.getDate() - 1);
    }
    if (period === "Weekly") {
      date.setDate(date.getDate() - 7);
    }
    if (period === "Monthly") {
      date.setDate(1);
      date.setMonth(date.getMonth() - 1);
    }
    if (period === "Yearly") {
      date.setMonth(0, 1);
      date.setFullYear(date.getFullYear() - 1);
    }
    timeline.value.currentDates[period] = date;
  };

  const goToToday = () => {
    const today = new Date();

    timeline.value.currentDates = {
      Daily: new Date(today),
      Weekly: new Date(today),
      Monthly: new Date(today),
      Yearly: new Date(today),
    };
  };

  return {
    selectedPeriod,
    currentDate,
    dateRange,
    changePeriod,
    nextPeriod,
    previousPeriod,
    goToToday,
  };
};
