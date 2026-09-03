// Relacionar datos con RAWG
export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);

  try {
    const data = await $fetch(
      "https://api.steampowered.com/IStoreService/GetAppList/v1/",
      {
        query: {
          key: config.steamApiKey,

          include_games: true,
          include_dlc: false,
          include_software: false,
          include_videos: false,
          include_hardware: false,

          max_results: 1000,
        },
      },
    );

    return data.response;
  } catch (error) {
    console.error("Steam GetAppList error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam API error",
    });
  }
});