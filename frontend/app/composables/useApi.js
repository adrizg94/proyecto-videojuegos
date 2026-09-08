export const useApi = () => {
  const config = useRuntimeConfig();

  // Cookie donde laravel almacena el token
  const xsrfToken = useCookie("XSRF-TOKEN");

  const apiFetch = async (url, options = {}) => {
    const method = (options.method ?? "GET").toUpperCase();

    const needsCsrf = !["GET", "HEAD", "OPTIONS"].includes(method);

    //Petición de cookie csrf a laravel
    if (needsCsrf && !xsrfToken.value) {
      await $fetch(`${config.public.api}/sanctum/csrf-cookie`, {
        credentials: "include",
      });

      refreshCookie("XSRF-TOKEN");
    }

    const headers = {
      Accept: "application/json",
      ...options.headers,
    };

    if (needsCsrf && xsrfToken.value) {
      headers["X-XSRF-TOKEN"] = decodeURIComponent(xsrfToken.value);
    }

    return await $fetch(`${config.public.api}/api/${url}`, {
      ...options,
      credentials: "include",
      headers,
    });
  };

  return {
    apiFetch,
  };
};
