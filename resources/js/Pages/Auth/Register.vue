<script setup>
import { ref } from "vue";
import axios from "axios"; // Importar Axios
import { useRouter } from "vue-router"; // Importar useRouter para redirección
import InputError from "@/Components/InputError.vue";

// Datos del formulario
const form = ref({
    name: "",
    adress: "",
    phone: "",
    email: "",
    terms: false,
});

// Errores del formulario
const errors = ref({});

// Inicializar router
const router = useRouter(); // Esto es necesario para usar la redirección

// Función para manejar el envío del formulario
async function submit() {
    try {
        // Limpiar errores previos
        errors.value = {};

        // Eliminar caracteres no numéricos del teléfono
        form.value.phone = form.value.phone.replace(/\D/g, "");

        // Enviar datos al backend usando Axios
        const response = await axios.post("/register", form.value);

        // Redirigir al usuario a la ruta de éxito
        router.push('/verify-account');  // Redirigir a la página de verificación
    } catch (error) {
        if (error.response && error.response.data.errors) {
            // Manejar errores de validación
            errors.value = error.response.data.errors;
            console.error("Errores:", errors.value);
        } else {
            console.error("Error inesperado:", error);
        }
    }
}
</script>

<template>
    <Head title="Register" />
    <div class="row g-0">
        <div class="col-12 col-md-6">
            <div class="card-one left-card">
                <!--daughter card-->
                <div
                    class="card-tres medi d-flex justify-content-center align-items-center"
                >
                    <div class="col-md-12 text-center">
                        <h3 class="text-ligth-blue fw-bold text-extra-large">
                            Join VetHub Rx. Simplify Your Pet's Care
                        </h3>
                        <p class="text-muted px-3 text-small">
                            Easy Mana prescriptions,<br />
                            supplements, and health <br />
                            recommendations all in one place.
                        </p>
                        <div class="d-flex justify-content-center mt-3">
                            <img
                                src="assets/img/img-register.png"
                                alt="Cute cat illustration used for the VetHub Rx login"
                                class="img-fluid"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card d-flex justify-content-center right-card">
                <!-- Card  -->
                <div
                    class="card-two login-cardd d-flex flex-column align-items-center justify-content-center">
                    <div class="logo mb-5 d-flex justify-content-center">
                        <img
                            src="assets/img/vethubrx-logo-white.png"
                            alt="Logo"
                            width="74%"/>
                    </div>
                    <form
                        class="w-100 text-small text-white"
                        @submit.prevent="submit">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-large"
                                >Full name</label>
                            <input
                                type="text"
                                name="username"
                                v-model="form.name"
                                class="custom-input rounded-5 w-100 p-2"
                                placeholder="Enter your name"
                                required/>
                            <InputError :message="errors.name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-large">Address</label>
                            <input
                                type="text"
                                name="adress"
                                v-model="form.adress"
                                class="custom-input rounded-5 w-100 p-2"
                                placeholder="Enter your address"
                            />
                            <InputError :message="errors.adress" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-large"
                                >Phone</label
                            >
                            <input
                                type="tel"
                                name="phone"
                                v-model="form.phone"
                                v-mask="'(###) ###-####'"
                                class="custom-input rounded-5 w-100 p-2"
                                placeholder="(123) 456-7890"
                            />
                            <InputError
                                :message="errors.phone"
                                class="mt-1 text-danger text-sm"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-large"
                                >E-mail</label
                            >
                            <input
                                type="email"
                                v-model="form.email"
                                class="custom-input rounded-5 w-100 p-2"
                                placeholder="Enter a valid email"
                                required
                            />
                            <InputError :message="errors.email" />
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="terms"
                                v-model="form.terms"
                            />
                            <label class="form-check-label" for="terms">
                                Agree to our
                                <a href="#" class="text-white"
                                    >Terms & Conditions</a
                                >
                                and
                                <a
                                    href="#"
                                    class="text-white text-decoration-none"
                                    >Privacy</a
                                >.
                            </label>
                            <InputError :message="errors.terms" />
                        </div>

                        <button
                            type="submit"
                            class="rounded-5 p-2 w-100 text-dark-blue fw-semibold"
                            
                        >
                            Register
                        </button>
                        <!-- Link para login dentro dHe un div -->
                        <div class="text-center mt-3">
                            <p class="text-white mb-0">
                                Already have an account?
                                <Link
                                    href="/login"
                                    class="fw-bold text-white text-decoration-underline">
                                    Sign in
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>