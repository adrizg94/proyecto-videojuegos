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

  const fetchUser = async () => {
    try {
      const response = await apiFetch("user");
      user.value = response.user;
    } catch {
      user.value = null;
    }
  };

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
