import { useEntityRelation } from "./useEntityRelation";

export const useCreatorFavorites = () => {
  const {
    entityNames: creatorNames,
    add: addCreator,
    remove: removeCreator,
    toggle: toggleCreator,
  } = useEntityRelation("favorite-creators", "creators");

  return {
    creatorNames,
    addCreator,
    removeCreator,
    toggleCreator,
  };
};
