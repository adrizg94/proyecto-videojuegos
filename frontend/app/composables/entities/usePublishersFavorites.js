import { useEntityRelation } from "./useEntityRelation";

export const usePublisherFavorites = () => {
  const {
    entityNames: publisherNames,
    add: addPublisher,
    remove: removePublisher,
    toggle: togglePublisher,
  } = useEntityRelation("favorite-publishers", "publishers");

  return {
    publisherNames,
    addPublisher,
    removePublisher,
    togglePublisher,
  };
};
