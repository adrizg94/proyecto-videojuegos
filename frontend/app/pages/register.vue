<template>
  <section class="flex justify-center items-center h-full">
    <div class="flex flex-col w-[25vw] h-[50vh] bg-surface rounded-lg py-3">
      <div class="flex justify-center items-center gap-1.5">
        <FontAwesomeIcon
          icon="fa-user-plus"
          class="text-3xl text-primary-light"
        />

        <h1 class="text-2xl font-bold text-center py-4">Sign Up</h1>
      </div>

      <form
        @submit.prevent="register"
        class="flex-1 flex flex-col justify-between mx-10"
      >
        <!-- Email -->
        <div class="flex flex-col gap-1">
          <label for="email"> Email </label>

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

        <!-- Username -->
        <div class="flex flex-col gap-1">
          <label for="username"> Username </label>

          <input
            v-model="username"
            id="username"
            name="username"
            type="text"
            autocomplete="username"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
          />
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-1">
          <label for="password"> Password </label>

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

        <!-- Password confirmation -->
        <div class="flex flex-col gap-1">
          <label for="password_confirmation"> Verify Password </label>

          <input
            v-model="passwordConfirmation"
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
            :class="
              passwordMismatch
                ? 'ring-2 ring-red-500 focus:ring-red-500'
                : 'focus:ring-primary'
            "
          />

          <p v-if="passwordMismatch" class="text-sm text-red-400">
            Passwords do not match.
          </p>
        </div>

        <button
          type="submit"
          class="w-fit font-semibold text-lg px-4 py-2 mx-auto my-2 rounded-lg hover:cursor-pointer bg-primary-light hover:bg-primary focus:outline-none transition-colors"
        >
          Register
        </button>

        <p class="text-sm text-right text-text-muted">
          Already have an account?

          <NuxtLink to="/login" class="hover:text-primary-light text-white">
            Log in
          </NuxtLink>
        </p>
      </form>
    </div>

    <Toast />
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "Register",
  },
]);

/*
|--------------------------------------------------------------------------
| Form state
|--------------------------------------------------------------------------
*/

const username = ref("");
const email = ref("");
const password = ref("");
const passwordConfirmation = ref("");

const passwordMismatch = ref(false);
const passwordNotLength = ref(false);

let passwordTimeout;

/*
|--------------------------------------------------------------------------
| Composables
|--------------------------------------------------------------------------
*/

const { showToast } = useToast();
const { setUser } = useAuth();
const { apiFetch } = useApi();

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

const register = async () => {
  if (password.value !== passwordConfirmation.value) {
    passwordMismatch.value = true;
    return;
  }

  if (password.value.length < 8) {
    passwordNotLength.value = true;
    return;
  }

  try {
    const response = await apiFetch("register", {
      method: "POST",
      body: {
        username: username.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      },
    });

    setUser(response.user);
    showToast(response.message);

    await navigateTo("/");
  } catch (error) {
    showToast(`ERROR: ${error.data?.message ?? "Error creating user"}`);
  }
};

/*
|--------------------------------------------------------------------------
| Password validation
|--------------------------------------------------------------------------
*/

watch([password, passwordConfirmation], () => {
  clearTimeout(passwordTimeout);

  passwordMismatch.value = false;
  passwordNotLength.value = false;

  passwordTimeout = setTimeout(() => {
    passwordMismatch.value =
      passwordConfirmation.value !== "" &&
      password.value !== passwordConfirmation.value;

    passwordNotLength.value = password.value.length < 8;
  }, 700);
});
</script>
