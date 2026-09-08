<template>
  <section class="flex justify-center items-center h-full">
    <div class="flex flex-col w-[25vw] h-[35vh] bg-surface rounded-lg py-3">
      <div class="flex justify-center items-center gap-1.5">
        <FontAwesomeIcon icon="fa-user" class="text-3xl text-primary-light" />
        <h1 class="text-2xl font-bold text-center py-4">Log in</h1>
      </div>
      <form
        @submit.prevent="login"
        class="flex-1 flex flex-col justify-between mx-10"
      >
        <div class="flex flex-col gap-1">
          <label for="email">Email</label>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
          />
        </div>
        <div class="flex flex-col gap-1">
          <label for="password">Password</label>
          <input
            v-model="password"
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
            :class="
              passwordNotLength
                ? 'ring-2 ring-red-500 focus:ring-red-500'
                : 'focus:ring-primary'
            "
          />
          <p v-if="passwordNotLength" class="text-sm text-red-400">
            The password field must be at least 8 characters.
          </p>
        </div>
        <button
          type="submit"
          class="w-fit font-semibold text-lg px-4 py-2 mx-auto my-2 rounded-lg hover:cursor-pointer bg-primary-light hover:bg-primary focus:outline-none transition-colors"
        >
          Login
        </button>
        <p class="text-sm text-right text-text-muted">
          Don't have an account?
          <NuxtLink to="/register" class="hover:text-primary-light text-white"
            >Register</NuxtLink
          >
        </p>
      </form>
    </div>
    <Toast />
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const email = ref("");
const password = ref("");
const passwordNotLength = ref(false);
let passwordTimeout;
const config = useRuntimeConfig();

const { showToast } = useToast();
const { setUser } = useAuth();
const { apiFetch } = useApi();

const login = async () => {
  if (password.value.length < 8) {
    passwordNotLength.value = true;
    return;
  }

  try {
    // await $fetch(`${config.public.api}/sanctum/csrf-cookie`, {
    //   credentials: "include",
    // });

    // const xsrfToken = useCookie("XSRF-TOKEN");

    // const response = await $fetch(`${config.public.api}/api/login`, {
    //   method: "POST",

    //   credentials: "include",

    //   headers: {
    //     Accept: "application/json",
    //     "X-XSRF-TOKEN": decodeURIComponent(xsrfToken.value),
    //   },

    //   body: {
    //     email: email.value,
    //     password: password.value,
    //   },
    // });

    const response = await apiFetch("login", {
      method: "POST",
      body: {
        email: email.value,
        password: password.value,
      },
    });

    setUser(response.user);
    showToast(response.message);
    await navigateTo("/");
  } catch (error) {
    showToast(`ERROR: ${error.data?.message ?? "Login failed"}`);
  }
};

watch([password], () => {
  clearTimeout(passwordTimeout);

  passwordNotLength.value = false;

  passwordTimeout = setTimeout(() => {
    passwordNotLength.value = password.value.length < 8;
  }, 700);
});
</script>
