export const useFavorites = () => {
  const {
    gameIds: favoriteIds,
    add: addFavorite,
    remove: removeFavorite,
    toggle: toggleFavorite,
  } = useGameRelation("favorites", "favorites");

  return {
    favoriteIds,
    addFavorite,
    removeFavorite,
    toggleFavorite,
  };
  // const { isAuthenticated } = useAuth();
  // const { apiFetch } = useApi();
  // const { showToast } = useToast();

  // const { data: favoriteIds } = useAsyncData(
  //   "favorites",
  //   async () => {
  //     if (!isAuthenticated.value) {
  //       return [];
  //     }

  //     const data = await apiFetch("favorites");

  //     return data.map((fav) => fav.rawg_id);
  //   },
  //   { server: false, default: () => [], watch: [isAuthenticated] },
  // );

  // const addFavorite = async (id, name, released, background) => {
  //   try {
  //     const response = await apiFetch("favorites", {
  //       method: "POST",

  //       body: {
  //         rawg_id: id,
  //         name: name,
  //         release_date: released,
  //         image: background,
  //       },
  //     });

  //     favoriteIds.value = [...favoriteIds.value, id];

  //     showToast(response.message);
  //   } catch (error) {
  //     showToast(
  //       `ERROR: ${error.data?.message ?? "Error adding game to favorites"}`,
  //     );
  //   }
  // };

  // const removeFavorite = async (id) => {
  //   try {
  //     const response = await apiFetch(`favorites/${id}`, {
  //       method: "DELETE",
  //     });
  //     favoriteIds.value = favoriteIds.value.filter(
  //       (favoriteId) => favoriteId !== id,
  //     );

  //     showToast(response.message);
  //   } catch (error) {
  //     showToast(
  //       `ERROR: ${
  //         error.data?.message ??
  //         error.message ??
  //         "Error removing game from favorites"
  //       }`,
  //     );
  //   }
  // };

  // const toggleFavorite = async (isFavorite, id, name, released, background) => {
  //   if (isFavorite) {
  //     await removeFavorite(id);
  //   } else {
  //     await addFavorite(id, name, released, background);
  //   }
  // };

  // return {
  //   favoriteIds,
  //   addFavorite,
  //   removeFavorite,
  //   toggleFavorite,
  // };
};
