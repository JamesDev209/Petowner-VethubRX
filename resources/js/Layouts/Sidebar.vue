<script setup>
import { ref } from 'vue';

const emit = defineEmits(['add-pet', 'pet-selected', 'close-sidebar']);
const props = defineProps({
  pets: {
    type: Array,
    default: () => [],
  },
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const selectedPetId = ref('');

// Método para manejar la selección de mascota
function handlePetSelection() {
  if (selectedPetId.value) {
    emit('pet-selected', selectedPetId.value);
  }
}
</script>

<template>
  <aside
    :class="['sidebar', { 'sidebar-open': props.isOpen, 'd-md-block': true }]"
    :style="{ display: props.isOpen ? 'block' : 'none' }"
    id="sidebar"
  >
    <!-- Botón cerrar solo en móvil -->
    <button class="btn-close-sidebar d-md-none" @click="emit('close-sidebar')">
      &times;
    </button>

    <div class="text-center mb-4 pt-4">
      <a href="/pets-dashboard">
        <img
          src="assets/img/vethubrx-logo-white.png"
          alt="VetHub Rx Logo"
          class="mb-1"
          width="76%"
        />
      </a>
    </div>

    <!-- Lógica condicional para mostrar el botón o el select -->
    <div class="d-flex justify-content-center align-items-center">
      <!-- Mostrar botón "Add your pet" si no hay mascotas -->
      <button
        v-if="props.pets.length === 0"
        class="btn btn-primary rounded-pill p-2 px-5 buttons-hover"
        @click="emit('add-pet')"
      >
        Add your pet
      </button>

      <!-- Mostrar select si hay mascotas -->
      <select
        v-else
        v-model="selectedPetId"
        @change="handlePetSelection"
        class="form-select rounded-pill px-3 py-2 w-75"
      >
        <option v-if="!selectedPetId" disabled value="">
          Select pet
        </option>
        <option
          v-for="pet in props.pets"
          :key="pet.id"
          :value="pet.id"
        >
          {{ pet.name }}
        </option>
      </select>
    </div>

    <div class="d-flex flex-column gap-3 mt-4">
    <!-- Botón Pets -->
    <router-link to="/pets" class="text-white text-decoration-none">
      <div class="d-flex justify-content-center align-items-center bg-light-blue p-2 rounded-pill">
        <img src="assets/img/prescriptions-icon.png" alt="" height="26px" class="me-2" />
        <span>Pets</span>
      </div>
    </router-link>

    <!-- Botón Prescriptions -->
    <router-link to="/prescriptions" class="text-white text-decoration-none">
      <div class="d-flex justify-content-center align-items-center bg-light-blue p-2 rounded-pill">
        <img src="assets/img/prescriptions-icon.png" alt="" height="26px" class="me-2" />
        <span>Prescriptions</span>
      </div>
    </router-link>
  </div>
  </aside>
</template>

