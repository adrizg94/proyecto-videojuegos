import { useEntityRelation } from "./useEntityRelation";

export const useDeveloperFavorites = () => {
  const {
    entityNames: developerNames,
    add: addDeveloper,
    remove: removeDeveloper,
    toggle: toggleDeveloper,
  } = useEntityRelation(
    "favorite-developers",
    "developers",
  );

  return {
    developerNames,
    addDeveloper,
    removeDeveloper,
    toggleDeveloper,
  };
};