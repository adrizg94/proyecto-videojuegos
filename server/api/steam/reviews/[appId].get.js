export default defineEventHandler(async (event) => {
  const appId = getRouterParam(event, "appId");

  if (!appId) {
    throw createError({
      statusCode: 400,
      statusMessage: "Steam App ID required",
    });
  }

  try {
    const data = await $fetch(
      `https://store.steampowered.com/appreviews/${appId}`,
      {
        query: {
          json: 1,
          language: "all",
        },
      },
    );
    return {
      appId,
      data,
    };
  } catch (error) {
    console.error("Steam API error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam API error",
    });
  }
});
