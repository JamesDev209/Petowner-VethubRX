<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <div class="row g-0">
    <div class="col-12 col-md-6">
      <div class="card-one left-card">
        <div class="card-tres medi d-flex justify-content-center align-items-center">
          <div class="col-md-12 text-center">
            <h3 class="text-ligth-blue fw-bold text-extra-large">
              Everything Your Pet <br> Needs, All in One Place!
            </h3>
            <p class="text-muted px-3 text-small text-ligth-blue">
              Manage prescriptions, supplements, and <br>expert recommendations because your pet's health starts with you.
            </p>
            <div class="d-flex justify-content-center mt-3">
              <img src="assets/img/gatosimg.png" alt="Cute cat illustration used for the VetHub Rx login" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="card d-flex justify-content-center right-card">
        <div class="card-dos medio d-flex flex-column justify-content-center align-items-center p-3">
          <div class="logo mb-5 mt-5">
            <img src="assets/img/vethubrx-logo-white.png" alt="" width="90%">
          </div>

          <form class="p-4 w-100" @submit.prevent="submit">
            <div class="mb-3">
              <label for="email" class="form-label fw-bold text-white text-large">Email Address</label>
              <input
                v-model="form.email"
                type="email"
                class="form-control rounded-pill p-2 fw-bold"
                id="email"
                placeholder="Examplemail@gmail.com"
                required
              >
              <InputError :message="form.errors.email" class="mt-1 text-warning text-small" />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-bold text-white text-large">Password</label>
              <input
                v-model="form.password"
                type="password"
                class="form-control rounded-pill p-2"
                id="password"
                placeholder="Password"
                required
              >
              <InputError :message="form.errors.password" class="mt-1 text-warning text-small" />
            </div>

            <div class="d-flex justify-content-center mb-3">
              <Link href="/forgot-password" class="forgot-password text-white text-small">Forgot Your password?</Link>
            </div>

            <button
              type="submit"
              class="btn bg-white w-100 fw-bold rounded-pill text-small text-dark-blue p-2"
              :disabled="form.processing"
            >
              Log In
            </button>

            <p class="text-center mt-3 mb-0 text-white text-small">
              Don't have an account?
              <Link href="/register" class="text-white fw-bold">Create</Link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
