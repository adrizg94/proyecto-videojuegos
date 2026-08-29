export const useShowMore = (content, limit) => {
  const showAll = ref(false);

  const hasMore = computed(() => {
    return (content.value?.length ?? 0) > limit;
  });

  const visibleContent = computed(() => {
    if (showAll.value) return content.value;

    const slicedContent = content.value.slice(0, limit);

    return typeof content.value === "string"
      ? slicedContent + (hasMore.value ? "..." : "")
      : slicedContent;
  });

  const toggleShowMore = () => {
    showAll.value = !showAll.value;
  };

  return {
    showAll,
    hasMore,
    visibleContent,
    toggleShowMore,
  };
};
