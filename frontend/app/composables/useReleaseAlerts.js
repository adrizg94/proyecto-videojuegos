export const useReleaseAlerts = () => {
  const {
    gameIds: releaseAlertIds,
    add: addReleaseAlert,
    remove: removeReleaseAlert,
    toggle: toggleReleaseAlert,
  } = useGameRelation("release-alerts", "release-alerts");

  return {
    releaseAlertIds,
    addReleaseAlert,
    removeReleaseAlert,
    toggleReleaseAlert,
  };
};
