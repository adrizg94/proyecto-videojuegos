export const useAuth = () => {
  const user = useState("auth-user", () => null);

  const isAuthenticated = computed(() => user.value !== null);

  const setUser = (newUser) => {
    user.value = newUser;
  };

  const clearUser = () => {
    user.value = null;
  };

  const { apiFetch } = useApi();

  // const fetchUser = async () => {
  //   const config = useRuntimeConfig();

  //   try {
  //     const currentUser = await $fetch(`${config.public.api}/api/user`, {
  //       credentials: "include",
  //       headers: {
  //         Accept: "application/json",
  //       },
  //     });

  //     user.value = currentUser;
  //   } catch {
  //     user.value = null;
  //   }
  // };
  const fetchUser = async () => {
    try {
      const response = await apiFetch("user");
      user.value = response.user;
    } catch {
      user.value = null;
    }
  };

  // const logout = async () => {
  //   const config = useRuntimeConfig();
  //   const xsrfToken = useCookie("XSRF-TOKEN");

  //   await $fetch(`${config.public.api}/api/logout`, {
  //     method: "POST",
  //     credentials: "include",

  //     headers: {
  //       Accept: "application/json",
  //       "X-XSRF-TOKEN": decodeURIComponent(xsrfToken.value),
  //     },
  //   });
  const logout = async () => {
    await apiFetch("logout", {
      method: "POST",
    });

    clearUser();

    await navigateTo("/");
  };

  return {
    user,
    setUser,
    clearUser,
    fetchUser,
    logout,
    isAuthenticated,
  };
};
