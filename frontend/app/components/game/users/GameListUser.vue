<template>
  <div class="flex flex-col gap-2">
    <span class="font-semibold"> Lists </span>

    <div class="flex flex-col">
      <button
        v-for="gameList in gameLists"
        :key="gameList.id"
        type="button"
        class="group flex w-fit items-center gap-2 rounded px-1 py-0.5 cursor-pointer"
        @click="toggleGameList(gameList)"
      >
        <span
          class="flex size-3.5 items-center justify-center rounded-sm border"
          :class="
            gameList.contains_game
              ? 'bg-primary-light border-primary-light'
              : 'border-text'
          "
        >
          <FontAwesomeIcon
            v-if="gameList.contains_game"
            icon="fa-check"
            class="text-[8px]"
          />
        </span>
        <span class="group-hover:text-primary-light transition-colors">
          {{ gameList.title }}
        </span>
      </button>

      <span v-if="gameLists.length === 0" class="text-sm text-text-muted">
        No lists created
      </span>
    </div>

    <button
      type="button"
      class="mt-3 w-fit text-sm font-semibold hover:text-primary-light cursor-pointer"
      @click="showCreateList = true"
    >
      + Create new list
    </button>
    <ModalGameListUser
      v-if="showCreateList"
      @close="showCreateList = false"
      @created="refreshGameLists"
    />
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import ModalGameListUser from "./ModalGameListUser.vue";

const props = defineProps({
  game: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(["createList"]);

const showCreateList = ref(false);

const { apiFetch } = useApi();
const { showToast } = useToast();

const { data: gameLists, refresh: refreshGameLists } = await useAsyncData(
  `game-lists-${props.game.id}`,
  () => apiFetch(`game-lists?rawg_id=${props.game.id}`),
  {
    server: false,
    default: () => [],
  },
);

const addGameToList = async (gameList) => {
  return await apiFetch(`game-lists/${gameList.id}/games`, {
    method: "POST",

    body: {
      rawg_id: props.game.id,
      name: props.game.name,
      release_date: props.game.released ?? null,
      image: props.game.background_image ?? null,
    },
  });
};

const removeGameFromList = async (gameList) => {
  return await apiFetch(`game-lists/${gameList.id}/games/${props.game.id}`, {
    method: "DELETE",
  });
};

const toggleGameList = async (gameList) => {
  try {
    let response;

    if (gameList.contains_game) {
      response = await removeGameFromList(gameList);
    } else {
      response = await addGameToList(gameList);
    }

    await refreshGameLists();

    showToast(response.message);
  } catch (error) {
    showToast(
      `ERROR: ${
        error.data?.message ?? error.message ?? "Error updating game list"
      }`,
    );
  }
};
</script>
