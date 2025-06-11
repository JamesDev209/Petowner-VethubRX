<script setup>
import { ref, nextTick, onMounted } from "vue";
import axios from "axios"; // Importar Axios
import Swal from "sweetalert2"; // Para mostrar mensajes de éxito/error

const code = ref(["", "", "", "", ""]);
const inputs = ref([]);

// Timer para bloquear el botón "Resend"
const isResending = ref(false);
const resendTimer = ref(0);
let timerInterval = null;

// Envía el código al backend para verificación
const submitCode = async () => {
    const fullCode = code.value.join("");

    if (fullCode.length !== 5) {
        Swal.fire({
            icon: "warning",
            title: "Incomplete Code",
            text: "Please enter all 5 digits",
            toast: true,
            position: "top-center",
            showConfirmButton: false,
            timer: 2000,
        });
        return;
    }

    try {
        console.log('Enviando código:', fullCode);
        const response = await axios.post("/verify-code", {
            verification_code: fullCode,
        });

        // Redirige al usuario si la verificación es exitosa
        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        } else {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: response.data.message || "Code verified successfully.",
                toast: true,
                position: "top-center",
                showConfirmButton: false,
                timer: 2000,
            });
        }
    } catch (error) {
        if (error.response && error.response.data.errors) {
            Swal.fire({
                icon: "warning",
                title: "Code Invalid",
                text: error.response.data.errors.verification_code,
                toast: true,
                position: "top-center",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        } else {
            console.error("Error inesperado:", error);
        }
    }
};

// Detecta la entrada y se mueve al siguiente campo
const handleInput = (event, index) => {
    const value = event.target.value;

    if (!/^\d$/.test(value)) {
        code.value[index] = "";
        return;
    }

    code.value[index] = value;

    if (index < code.value.length - 1) {
        nextTick(() => {
            inputs.value[index + 1]?.focus();
        });
    }
};

// Vuelve al campo anterior si se borra
const handleBackspace = (event, index) => {
    if (code.value[index] === "" && index > 0) {
        nextTick(() => {
            inputs.value[index - 1]?.focus();
        });
    }
};

// Permite pegar el código completo de una vez
const handlePaste = (event) => {
    const pasted = event.clipboardData.getData("Text").trim();
    if (/^\d{5}$/.test(pasted)) {
        for (let i = 0; i < 5; i++) {
            code.value[i] = pasted[i];
        }
        nextTick(() => {
            inputs.value[4]?.focus();
        });
    }
};

// Reenviar el código al usuario
const resendCode = async () => {
    if (isResending.value) return;

    isResending.value = true;
    resendTimer.value = 30; // segundos para esperar

    // Iniciar cuenta regresiva
    timerInterval = setInterval(() => {
        resendTimer.value--;
        if (resendTimer.value <= 0) {
            clearInterval(timerInterval);
            isResending.value = false;
        }
    }, 1000);

    try {
        const response = await axios.post("/resend-verification-code");
        Swal.fire({
            icon: "success",
            title: "Código reenviado",
            text: response.data.message || "Revisa tu correo (también en spam).",
            toast: true,
            position: "top-center",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: error.response?.data?.message || "No se pudo reenviar el código.",
            toast: true,
            position: "top-center",
            showConfirmButton: false,
            timer: 2000,
        });
    }
};

// Autofocus en el primer campo al cargar
onMounted(() => {
    nextTick(() => {
        if (inputs.value.length > 0) {
            inputs.value[0]?.focus();
        }
    });
});
</script>

<template>
    <div class="row g-0">
        <div class="col-12 col-md-6">
            <!-- Left card -->
            <div class="card-one left-card">
                <div
                    class="card-tres medi d-flex justify-content-center align-items-center"
                >
                    <div class="col-md-12 text-center">
                        <h3 class="text-ligth-blue fw-bold text-extra-large">
                            Join VetHub Rx. Simplify Your Pet's Care
                        </h3>
                        <p class="text-muted px-3 text-small">
                            Easy Mana prescriptions, <br />
                            supplements, and health <br />
                            recommendations all in one place
                        </p>
                        <div class="d-flex justify-content-center mt-3">
                            <img
                                src="assets/img/img-register.png"
                                alt="Cute cat illustration"
                                class="img-fluid"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <!-- Right card -->
            <div class="card d-flex justify-content-center right-card">
                <div
                    class="card-three verify-email bg-blue-dark d-flex justify-content-center flex-column align-items-center">
                    <div class="logo mb-5">
                        <img
                            src="assets/img/vethubrx-logo-white.png"
                            alt="VetHub Logo"
                            width="88%"/>
                    </div>
                    <h5 class="fw-bold text-white mb-3">Verify your mail</h5>
                    <p class="text-white text-small text-center mt-3">
                        We've sent a 5-digit code to your email. <br />
                        If you don't see it, check your spam folder.
                    </p>

                    <form
                        class="d-flex flex-column align-items-center mb-4"
                        @submit.prevent="submitCode"
                    >
                        <h4 class="fw-bold text-white text-large mb-2">Code</h4>
                        <div class="d-flex">
                            <input
                                v-for="(digit, index) in code"
                                :key="index"
                                v-model="code[index]"
                                ref="inputs"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="1"
                                class="code-input"
                                @input="handleInput($event, index)"
                                @keydown.backspace="
                                    handleBackspace($event, index)
                                "
                                @paste="handlePaste($event)"
                            />
                        </div>

                        <!-- Link to send code -->
                        <p class="text-white mt-3 text-small text-center">
                            Didn't receive the code?
                            <a
                                href="#"
                                class="text-white fw-bold"
                                @click.prevent="resendCode"
                                :class="{ disabled: isResending }"
                            >
                                Resend<span v-if="isResending">
                                    ({{ resendTimer }}s)</span
                                >
                            </a>
                        </p>

                        <button
                            type="submit"
                            class="verify-btn fw-semibold mt-3 rounded-5 py-2 text-dark-blue w-100"
                        >
                            Verify
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
