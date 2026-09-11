export const useBreadcrumbs = () => {
  const route = useRoute();

  const breadcrumbs = useState("breadcrumbs", () => ({
    path: "",
    items: [],
  }));

  const setBreadcrumbs = (items) => {
    breadcrumbs.value = {
      path: route.path,
      items,
    };
  };

  const clearBreadcrumbs = () => {
    breadcrumbs.value = {
      path: "",
      items: [],
    };
  };

  const currentBreadcrumbs = computed(() => {
    if (breadcrumbs.value.path !== route.path) {
      return [];
    }

    return breadcrumbs.value.items;
  });

  return {
    breadcrumbs: currentBreadcrumbs,
    setBreadcrumbs,
    clearBreadcrumbs,
  };
};
