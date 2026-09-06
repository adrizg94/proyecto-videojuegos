// Datos de la ficha de steam
export default defineEventHandler(async (event) => {
  const appId = getRouterParam(event, "appId");

  if (!appId) {
    throw createError({
      statusCode: 400,
      statusMessage: "Steam App ID required",
    });
  }

  try {
    const response = await $fetch(
      "https://store.steampowered.com/api/appdetails",
      {
        // query: {
        //   appids: appId,
        //   cc: "es",
        //   l: "spanish",
        // },
        query: {
          appids: appId,
          cc: "es",
          l: "english",
        },
      },
    );

    return response[appId]?.data ?? null;
  } catch (error) {
    console.error("Steam appdetails error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam Store API error",
    });
  }
});
