export const useEntityRelation = (key, endpoint) => {
  const { isAuthenticated } = useAuth();
  const { apiFetch } = useApi();
  const { showToast } = useToast();

  const { data: entityNames } = useAsyncData(
    key,
    async () => {
      if (!isAuthenticated.value) {
        return [];
      }

      const data = await apiFetch(endpoint);

      return data.map((entity) => entity.name);
    },
    {
      server: false,
      default: () => [],
      watch: [isAuthenticated],
    },
  );

  const add = async (rawgId, name, image) => {
    try {
      const response = await apiFetch(endpoint, {
        method: "POST",
        body: {
          rawg_id: rawgId,
          name,
          image: image ?? null,
        },
      });

      entityNames.value = [...entityNames.value, name];

      showToast(response.message);
    } catch (error) {
      showToast(
        `ERROR: ${
          error.data?.message ??
          error.message ??
          `Error adding ${endpoint} to favorites`
        }`,
      );
    }
  };

  const remove = async (name) => {
    try {
      const response = await apiFetch(
        `${endpoint}/${encodeURIComponent(name)}`,
        {
          method: "DELETE",
        },
      );

      entityNames.value = entityNames.value.filter(
        (entityName) => entityName !== name,
      );

      showToast(response.message);
    } catch (error) {
      showToast(
        `ERROR: ${
          error.data?.message ??
          error.message ??
          `Error removing ${endpoint} from favorites`
        }`,
      );
    }
  };

  const toggle = async (isActive, rawgId, name, image) => {
    if (isActive) {
      await remove(name);
    } else {
      await add(rawgId, name, image);
    }
  };

  return {
    entityNames,
    add,
    remove,
    toggle,
  };
};
