export default defineEventHandler(async () => {
  const appid = "1091500";

  const response = await $fetch(
    "https://store.steampowered.com/api/appdetails",
    {
      query: {
        appids: appid,
      },
    },
  );

  const game = response[appid]?.data;

  const movie =
    game?.movies?.find((movie) =>
      movie.name.toLowerCase().includes("launch"),
    ) ??
    game?.movies?.find((movie) => movie.highlight) ??
    game?.movies?.[0];

  return {
    name: game?.name,
    trailer: movie?.hls_h264 ?? null,
    poster: movie?.thumbnail ?? null,
  };
});
