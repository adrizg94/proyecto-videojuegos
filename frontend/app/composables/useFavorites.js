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
};
