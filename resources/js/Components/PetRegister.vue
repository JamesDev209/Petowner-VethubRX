<script>
import { ref, reactive, computed, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import InputError from "@/Components/InputError.vue";

const months = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

const days = Array.from({ length: 31 }, (_, i) => i + 1);
const years = Array.from({ length: 30 }, (_, i) => new Date().getFullYear() - i);

const isModalOpen = ref(false);

const form = reactive({
  name: "",
  species: "Dog",
  breed: "",
  dob: "",
  dob_day: "",
  dob_month: "",
  dob_year: "",
  gender: "male",
  weight: "",
  weight_unit: "",
  allergies: "",
  health_notes: "",
  errors: {}
});

const formattedDOB = computed(() => {
  if (
    form.dob_month &&
    form.dob_day &&
    form.dob_year &&
    months.includes(form.dob_month) &&
    form.dob_day >= 1 &&
    form.dob_day <= 31 &&
    years.includes(parseInt(form.dob_year))
  ) {
    const monthIndex = months.indexOf(form.dob_month) + 1;
    const month = monthIndex.toString().padStart(2, "0");
    const day = form.dob_day.toString().padStart(2, "0");
    return `${form.dob_year}-${month}-${day}`;
  }
  return "";
});

watch([() => form.dob_month, () => form.dob_day, () => form.dob_year], () => {
  form.dob = formattedDOB.value;
  if (form.errors.dob && form.dob) {
    delete form.errors.dob;
  }
});

const validateForm = () => {
  form.errors = {};

  if (!form.name) form.errors.name = "The name field is required.";
  if (!form.species) form.errors.species = "The species field is required.";
  if (!form.gender) form.errors.gender = "The gender field is required.";
  if (!form.dob) form.errors.dob = "The date of birth is required and must be valid.";
  if (!form.weight) form.errors.weight = "The weight field is required.";
  if (!form.weight_unit || form.weight_unit === "Units") {
    form.errors.weight_unit = "The weight unit is required.";
  }

  return Object.keys(form.errors).length === 0;
};

const submit = async () => {
  if (!validateForm()) return;

  try {
    await axios.post("/pets", { ...form, errors: undefined });

    isModalOpen.value = false;
    resetForm();

    Swal.fire({
      toast: true,
      position: "center",
      icon: "success",
      title: "Registered pet.",
      showConfirmButton: false,
      timer: 1000,
      timerProgressBar: true,
      width: "16rem",
      background: "#14124c",
      color: "#ffffff",
      padding: "0.75rem",
      customClass: {
        popup: "swal2-mini-toast",
        title: "swal2-mini-title",
      },
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
      },
    });
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      form.errors = error.response.data.errors;
    } else {
      console.error("Unexpected error", error);
    }
  }
};

const resetForm = () => {
  form.name = "";
  form.species = "Dog";
  form.breed = "";
  form.dob = "";
  form.dob_day = "";
  form.dob_month = "";
  form.dob_year = "";
  form.gender = "male";
  form.weight = "";
  form.weight_unit = "";
  form.allergies = "";
  form.health_notes = "";
  form.errors = {};
};

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

export default {
  components: {
    InputError,
  },
  setup() {
    return {
      months,
      days,
      years,
      form,
      isModalOpen,
      formattedDOB,
      openModal,
      closeModal,
      submit,
    };
  },
};
</script>


<template>
    <!-- Modal -->
    <div
        v-if="isModalOpen"
        class="modal d-block"
        id="registerModal"
        tabindex="-1"
        aria-labelledby="registerModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-5">
                <div
                    class="modal-header d-flex justify-content-between align-items-center"
                >
                    <h5
                        class="modal-title fw-bold text-ligth-blue d-flex justify-content-center"
                        id="registerModalLabel"
                    >
                        Pet Register
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        @click="isModalOpen = false"
                    ></button>
                </div>

                <div class="modal-body text-dark-blue text-small fw-semibold">
                    <form @submit.prevent="submit">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label class="form-label text-dark-blue text-small">
                                Name<span class="text-danger">*</span>
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                name="username"
                                class="form-control bg-error rounded-5 bg-light"
                                placeholder="Enter your name"
                                required
                            />
                            <InputError
                                :message="form.errors.name"
                                class="text-danger"
                            />
                        </div>

                        <!-- Especie y Raza -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Species</label>
                                <input
                                    v-model="form.species"
                                    type="text"
                                    name="username"
                                    class="form-control rounded-5 bg-light text-small"
                                    placeholder="Species"
                                />
                                <InputError
                                    :message="form.errors.species"
                                    class="text-danger"
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"
                                    >Breed (optional)</label
                                >
                                <input
                                    v-model="form.breed"
                                    type="text"
                                    name="username"
                                    class="form-control rounded-5 bg-light text-small"
                                    placeholder="Breed"
                                />
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-3">
                            <label class="form-label"
                                >Date of Birth<span class="text-danger"
                                    >*</span
                                ></label
                            >
                            <div class="d-flex gap-3">
                                <!-- Mes -->
                                <select
                                    v-model="form.dob_month"
                                    class="form-select text-small bg-light rounded-5 text-secondary"
                                >
                                    <option disabled value="">Month</option>
                                    <option
                                        v-for="month in months"
                                        :key="month"
                                        :value="month"
                                    >
                                        {{ month }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.dob_month"
                                    class="text-danger"
                                />

                                <!-- Día -->
                                <select
                                    v-model="form.dob_day"
                                    class="form-select text-small bg-light rounded-5 text-secondary"
                                >
                                    <option disabled value="">DD</option>
                                    <option
                                        v-for="day in days"
                                        :key="day"
                                        :value="day"
                                    >
                                        {{ day }}
                                    </option>
                                    <InputError
                                        :message="form.errors.dob_day"
                                    />
                                </select>

                                <!-- Año -->
                                <select
                                    v-model="form.dob_year"
                                    class="form-select text-small bg-light rounded-5 text-secondary"
                                >
                                    <option disabled value="">YYYY</option>
                                    <option
                                        v-for="year in years"
                                        :key="year"
                                        :value="year"
                                    >
                                        {{ year }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.dob_year" />
                            </div>
                            <!-- Error general para la fecha -->
                            <InputError
                                :message="form.errors.dob"
                                class="text-danger"
                            />
                        </div>

                        <!-- Género -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Gender</label><br />
                                <div class="form-check form-check-inline">
                                    <input
                                        v-model="form.gender"
                                        class="form-check-input"
                                        type="radio"
                                        name="gender"
                                        value="male"
                                    />
                                    <label class="form-check-label">Male</label>
                                </div>
                                <div class="form-check form-check-inline m-2">
                                    <input
                                        v-model="form.gender"
                                        class="form-check-input"
                                        type="radio"
                                        name="gender"
                                        value="female"
                                    />
                                    <label class="form-check-label"
                                        >Female</label
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Peso y Unidad -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Weight</label>
                                <div class="d-flex gap-2">
                                    <input
                                        v-model="form.weight"
                                        type="number"
                                        class="text-small form-control input-bg rounded-5"
                                        placeholder="Number"
                                        step="0.01"
                                    />
                                    <select
                                        v-model="form.weight_unit"
                                        class="form-select input-bg rounded-5 text-small"
                                    >
                                        <option disabled value="">Units</option>
                                        <option value="kg">kg</option>
                                        <option value="lbs">lbs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Alergias -->
                        <div class="mb-3">
                            <label class="form-label">Allergies</label>
                            <input
                                v-model="form.allergies"
                                type="text"
                                class="form-control bg-error rounded-5 input-bg text-secondary text-small"
                                placeholder="Allergies"
                            />
                        </div>

                        <!-- Notas de Salud -->
                        <div class="mb-3">
                            <label class="form-label">Health Notes</label>
                            <input
                                v-model="form.health_notes"
                                type="text"
                                class="form-control bg-error text-secondary rounded-5 input-bg text-small"
                                placeholder="Health Notes"
                            />
                        </div>

                        <!-- Boton de Registro -->
                        <div class="mb-3 d-flex justify-content-center">
                            <button
                                type="submit"
                                class="btn btn-register rounded-pill bg-blue-dark text-white buttons-hover p-2 w-50 text-small"
                            >
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
