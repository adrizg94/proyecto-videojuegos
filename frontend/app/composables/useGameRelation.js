export const useGameRelation = (key, endpoint) => {
  const { isAuthenticated } = useAuth();
  const { apiFetch } = useApi();
  const { showToast } = useToast();

  const { data: gameIds } = useAsyncData(
    key,
    async () => {
      if (!isAuthenticated.value) {
        return [];
      }

      const data = await apiFetch(endpoint);

      return data.map((game) => game.rawg_id);
    },
    {
      server: false,
      default: () => [],
      watch: [isAuthenticated],
    },
  );

  const add = async (id, name, released, background) => {
    try {
      const response = await apiFetch(endpoint, {
        method: "POST",

        body: {
          rawg_id: id,
          name,
          release_date: released,
          image: background,
        },
      });

      gameIds.value = [...gameIds.value, id];

      showToast(response.message);
    } catch (error) {
      showToast(
        `ERROR: ${
          error.data?.message ??
          error.message ??
          `Error adding game to ${endpoint}`
        }`,
      );
    }
  };

  const remove = async (id) => {
    try {
      const response = await apiFetch(`${endpoint}/${id}`, {
        method: "DELETE",
      });

      gameIds.value = gameIds.value.filter(
        (gameId) => gameId !== id,
      );

      showToast(response.message);
    } catch (error) {
      showToast(
        `ERROR: ${
          error.data?.message ??
          error.message ??
          `Error removing game from ${endpoint}`
        }`,
      );
    }
  };

  const toggle = async (
    isActive,
    id,
    name,
    released,
    background,
  ) => {
    if (isActive) {
      await remove(id);
    } else {
      await add(id, name, released, background);
    }
  };

  return {
    gameIds,
    add,
    remove,
    toggle,
  };
};