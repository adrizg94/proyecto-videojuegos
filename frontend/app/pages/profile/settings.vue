<template>
  <main class="w-full max-w-3xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else>
      <!-- Header -->
      <header class="mb-8">
        <NuxtLink
          to="/profile"
          class="inline-flex items-center gap-2 text-text-muted hover:text-white transition-colors"
        >
          <FontAwesomeIcon icon="fa-arrow-left-long" />
          Back to profile
        </NuxtLink>

        <h1 class="mt-5 text-3xl font-bold">Settings</h1>

        <p class="mt-1 text-text-muted">
          Manage your account information and password.
        </p>
      </header>

      <!-- Account information -->
      <section class="rounded-2xl bg-surface p-6 mb-8">
        <div class="mb-6">
          <h2 class="text-xl font-bold">Account information</h2>

          <p class="mt-1 text-sm text-text-muted">
            Update your username and email address.
          </p>
        </div>

        <form class="flex flex-col gap-5" @submit.prevent="updateAccount">
          <!-- Username -->
          <div class="flex flex-col gap-2">
            <label for="username" class="font-medium"> Username </label>

            <input
              id="username"
              v-model="accountForm.username"
              type="text"
              autocomplete="username"
              class="w-full rounded-lg bg-background border border-text-muted/30 px-4 py-2.5 outline-none focus:border-primary-light transition-colors"
            />

            <p v-if="accountErrors.username" class="text-sm text-red-400">
              {{ accountErrors.username[0] }}
            </p>
          </div>

          <!-- Email -->
          <div class="flex flex-col gap-2">
            <label for="email" class="font-medium"> Email </label>

            <input
              id="email"
              v-model="accountForm.email"
              type="email"
              autocomplete="email"
              class="w-full rounded-lg bg-background border border-text-muted/30 px-4 py-2.5 outline-none focus:border-primary-light transition-colors"
            />

            <p v-if="accountErrors.email" class="text-sm text-red-400">
              {{ accountErrors.email[0] }}
            </p>
          </div>

          <div class="flex justify-end mt-2">
            <button
              type="submit"
              :disabled="savingAccount"
              class="rounded-lg bg-primary px-5 py-2.5 font-medium cursor-pointer hover:bg-primary-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ savingAccount ? "Saving..." : "Save changes" }}
            </button>
          </div>
        </form>
      </section>

      <!-- Password -->
      <section class="rounded-2xl bg-surface p-6">
        <div class="mb-6">
          <h2 class="text-xl font-bold">Change password</h2>

          <p class="mt-1 text-sm text-text-muted">
            Use your current password to set a new one.
          </p>
        </div>

        <form class="flex flex-col gap-5" @submit.prevent="updatePassword">
          <!-- Current password -->
          <div class="flex flex-col gap-2">
            <label for="current-password" class="font-medium">
              Current password
            </label>

            <input
              id="current-password"
              v-model="passwordForm.current_password"
              type="password"
              autocomplete="current-password"
              class="w-full rounded-lg bg-background border border-text-muted/30 px-4 py-2.5 outline-none focus:border-primary-light transition-colors"
            />

            <p
              v-if="passwordErrors.current_password"
              class="text-sm text-red-400"
            >
              {{ passwordErrors.current_password[0] }}
            </p>
          </div>

          <!-- New password -->
          <div class="flex flex-col gap-2">
            <label for="password" class="font-medium"> New password </label>

            <input
              id="password"
              v-model="passwordForm.password"
              type="password"
              autocomplete="new-password"
              class="w-full rounded-lg bg-background border border-text-muted/30 px-4 py-2.5 outline-none focus:border-primary-light transition-colors"
            />

            <p v-if="passwordErrors.password" class="text-sm text-red-400">
              {{ passwordErrors.password[0] }}
            </p>
          </div>

          <!-- Password confirmation -->
          <div class="flex flex-col gap-2">
            <label for="password-confirmation" class="font-medium">
              Confirm new password
            </label>

            <input
              id="password-confirmation"
              v-model="passwordForm.password_confirmation"
              type="password"
              autocomplete="new-password"
              class="w-full rounded-lg bg-background border border-text-muted/30 px-4 py-2.5 outline-none focus:border-primary-light transition-colors"
            />
          </div>

          <div class="flex justify-end mt-2">
            <button
              type="submit"
              :disabled="savingPassword"
              class="rounded-lg bg-primary px-5 py-2.5 font-medium cursor-pointer hover:bg-primary-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ savingPassword ? "Changing..." : "Change password" }}
            </button>
          </div>
        </form>
      </section>

      <Toast />
    </div>
  </main>
</template>

<script setup>
/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

const { setBreadcrumbs } = useBreadcrumbs();

setBreadcrumbs([
  {
    label: "My profile",
    to: "/profile",
  },
  {
    label: "Settings",
  },
]);

/*
|--------------------------------------------------------------------------
| Composables
|--------------------------------------------------------------------------
*/

const { apiFetch } = useApi();
const { user, fetchUser } = useAuth();
const { showToast } = useToast();

/*
|--------------------------------------------------------------------------
| Page state
|--------------------------------------------------------------------------
*/

const pending = ref(true);
const savingAccount = ref(false);
const savingPassword = ref(false);

/*
|--------------------------------------------------------------------------
| Account form
|--------------------------------------------------------------------------
*/

const accountForm = reactive({
  username: "",
  email: "",
});

const accountErrors = ref({});

/*
|--------------------------------------------------------------------------
| Password form
|--------------------------------------------------------------------------
*/

const passwordForm = reactive({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const passwordErrors = ref({});

/*
|--------------------------------------------------------------------------
| Account update
|--------------------------------------------------------------------------
*/

const updateAccount = async () => {
  accountErrors.value = {};
  savingAccount.value = true;

  try {
    const response = await apiFetch("profile", {
      method: "PUT",
      body: {
        username: accountForm.username,
        email: accountForm.email,
      },
    });

    // Actualizar useAuth para reflejar inmediatamente
    // el nuevo username en la TopBar.
    user.value = response.user;

    showToast(response.message);
  } catch (error) {
    accountErrors.value = error.data?.errors ?? {};

    showToast(
      `ERROR: ${
        error.data?.message ??
        error.message ??
        "Error updating account information"
      }`,
    );
  } finally {
    savingAccount.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Password update
|--------------------------------------------------------------------------
*/

const updatePassword = async () => {
  passwordErrors.value = {};
  savingPassword.value = true;

  try {
    const response = await apiFetch("profile/password", {
      method: "PUT",
      body: {
        current_password: passwordForm.current_password,
        password: passwordForm.password,
        password_confirmation: passwordForm.password_confirmation,
      },
    });

    passwordForm.current_password = "";
    passwordForm.password = "";
    passwordForm.password_confirmation = "";

    showToast(response.message);
  } catch (error) {
    passwordErrors.value = error.data?.errors ?? {};

    showToast(
      `ERROR: ${
        error.data?.message ?? error.message ?? "Error changing password"
      }`,
    );
  } finally {
    savingPassword.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Page initialization
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  try {
    if (!user.value) {
      await fetchUser();
    }

    if (!user.value) {
      await navigateTo("/login");
      return;
    }

    accountForm.username = user.value.username;
    accountForm.email = user.value.email;
  } catch (error) {
    console.error("Error loading settings:", error);
  } finally {
    pending.value = false;
  }
});
</script>
