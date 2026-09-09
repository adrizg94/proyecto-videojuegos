<template>
  <div class="flex flex-col gap-2">
    <span class="font-semibold"> Status </span>

    <div class="flex flex-col">
      <label
        v-for="status in statuses"
        :key="status.value"
        class="group flex w-fit items-center gap-2 rounded px-1 py-0.5 cursor-pointer hover:text-primary-light"
      >
        <input
          v-model="selectedStatus"
          type="radio"
          name="gameStatus"
          :value="status.value"
          @change="changeStatus(status.value)"
        />
        <span>
          {{ status.label }}
        </span>
      </label>
      <button
        v-if="selectedStatus"
        type="button"
        title="Remove status"
        class="self-end mr-2 p-1.5 w-fit text-md text-text-muted hover:text-red-400 cursor-pointer"
        @click="removeStatus"
      >
        <FontAwesomeIcon icon="fa-trash" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
  game: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(["statusChanged"]);

const { apiFetch } = useApi();
const { showToast } = useToast();

const statuses = [
  { value: "backlog", label: "Backlog" },
  { value: "playing", label: "Playing" },
  { value: "completed", label: "Completed" },
  { value: "paused", label: "Paused" },
  { value: "dropped", label: "Dropped" },
];

const selectedStatus = ref(null);

const { data: gameStatus, refresh: refreshGameStatus } = await useAsyncData(
  `game-status-${props.game.id}`,
  () => apiFetch(`game-statuses?rawg_id=${props.game.id}`),
  {
    server: false,
    default: () => null,
  },
);

const removeStatus = async () => {
  try {
    await refreshGameStatus();
    const response = await apiFetch(`game-statuses/${gameStatus.value.id}`, {
      method: "DELETE",
    });

    selectedStatus.value = null;

    showToast(response.message);
  } catch (error) {
    showToast(
      `ERROR: ${
        error.data?.message ?? error.message ?? "Error removing game status"
      }`,
    );
  }
};

const changeStatus = async (status) => {
  try {
    const response = await apiFetch("game-statuses", {
      method: "POST",
      body: {
        rawg_id: props.game.id,
        name: props.game.name,
        release_date: props.game.released,
        image: props.game.background_image ?? null,
        status,
      },
    });

    selectedStatus.value = status;

    emit("statusChanged", status);

    showToast(response.message);
  } catch (error) {
    showToast(
      `ERROR: ${error.data?.message ?? error.message ?? "Error changing game status"}`,
    );
  }
};

watch(
  gameStatus,
  (value) => {
    selectedStatus.value = value?.status ?? null;
  },
  { immediate: true },
);
</script>
