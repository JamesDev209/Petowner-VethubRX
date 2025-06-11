<script setup>
import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";

const form = ref({
    password: "",
    password_confirmation: "",
});

const errors = ref({});
const router = useRouter();

async function submitForm() {
    try {
        const response = await axios.post("/set-password", form.value);

        // Mostrar mensaje de éxito
        Swal.fire({
            icon: "success",
            title: "Éxito",
            text: response.data.message || "Contraseña actualizada correctamente.",
        });

        // Redirigir al dashboard
        router.push({ name: "pets.dashboard" });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            // Manejar errores de validación
            errors.value = error.response.data.errors;
        } else {
            // Manejar errores generales
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error.response?.data?.error || "Ocurrió un error inesperado.",
            });
        }
    }
}
</script>
<template>
    <div class="row g-0">
        <!-- Left side -->
        <div class="col-12 col-md-6">
            <div class="card-one left-card">
                <div
                    class="card-tres medi d-flex justify-content-center align-items-center"
                >
                    <div class="col-md-12 text-center">
                        <h3 class="text-ligth-blue fw-bold text-extra-large">
                            Everything Your Pet <br />
                            Needs, All in One Place!
                        </h3>
                        <p class="text-muted px-3 text-small text-ligth-blue">
                            Manage prescriptions, supplements, and expert
                            recommendations because your pet's health starts
                            with you.
                        </p>
                        <div class="d-flex justify-content-center mt-3">
                            <img
                                src="assets/img/gatosimg.png"
                                alt="Cute cat illustration"
                                class="img-fluid"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side -->
        <div class="col-sm-6">
            <div class="card d-flex justify-content-center right-card">
                <div
                    class="card-dos medio d-flex flex-column justify-content-center align-items-center p-3"
                >
                    <div class="logo mb-5 mt-5">
                        <img
                            src="assets/img/vethubrx-logo-white.png"
                            alt="VetHub Rx"
                            width="90%"
                        />
                    </div>

                    <!-- Form Vue -->
                    <form
                        class="p-4 w-100"
                        @submit.prevent="submitForm"
                    >
                        <div
                            v-if="errors.error"
                            class="alert alert-danger mb-3"
                        >
                            {{ errors.error }}
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label
                                for="password"
                                class="form-label fw-semibold text-white text-large"
                                >New Password</label
                            >
                            <input
                                type="password"
                                id="password"
                                class="form-control rounded-pill p-2"
                                v-model="form.password"
                                placeholder="Enter new password"
                            />
                            <div
                                v-if="errors.password"
                                class="text-danger text-small mt-1"
                            >
                                {{ errors.password }}
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label
                                for="password_confirmation"
                                class="form-label fw-semibold text-white text-large"
                                >Confirm Password</label
                            >
                            <input
                                type="password"
                                id="password_confirmation"
                                class="form-control rounded-pill p-2"
                                v-model="form.password_confirmation"
                                placeholder="Confirm password"
                            />
                            <div
                                v-if="errors.password_confirmation"
                                class="text-danger text-small mt-1"
                            >
                                {{ errors.password_confirmation }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button
                                type="submit"
                                class="btn bg-white w-50 fw-semibold rounded-pill text-small text-dark-blue p-2"
                                :disabled="loading"
                            >
                                Confirm Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>