<template>
  <!-- Card container (migrado desde la vista padre) -->
  <div class="card shadow-sm border rounded p-3 mb-4">
    <!-- Header con título y botón -->
    <div class="d-flex justify-content-center bg-ligth-blue align-items-center mb-3">
      
    </div>
<!-- Search -->
<div class="search-container row mb-3">
  <div class="col-12 col-md-8 mb-2 mb-md-0">
    <input
      type="text"
      v-model="searchTerm"
      placeholder="Search by name, species, or breed..."
      class="form-control"
      aria-label="Search Pet"
      role="searchbox"
    />
  </div>
  <div class="col-12 col-md-4 d-flex justify-content-md-end">
    <button
      class="btn btn-sm bg-blue-dark text-white rounded-pill buttons-hover w-100 w-md-auto"
      @click="$emit('add-pet')"
    >
      ➕ Add Pet
    </button>
  </div>
</div>

    <!-- Table -->
    <div class="table-container overflow-auto">
      <table class="table table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Specie</th>
            <th>Race</th>
            <th>Date of birth.</th>
            <th>Gender</th>
            <th>Weight</th>
            <th>Allergies</th>
            <th>Health notes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pet in filteredPets" :key="pet.id">
            <td>{{ pet.name || '-' }}</td>
            <td>{{ pet.species || '-' }}</td>
            <td>{{ pet.breed || '-' }}</td>
            <td>{{ formatDob(pet) }}</td>
            <td>{{ pet.gender || '-' }}</td>
            <td>{{ pet.weight ? `${pet.weight} ${pet.weight_unit || ''}` : '-' }}</td>
            <td>{{ pet.allergies || '-' }}</td>
            <td>{{ pet.health_notes || '-' }}</td>
          </tr>
          <tr v-if="filteredPets.length === 0">
            <td colspan="8" class="text-center text-muted">No pets found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  pets: {
    type: Array,
    required: true,
  },
  selectedPetId: {
    type: [String, Number],
    default: '',
  },
})


const searchTerm = ref('')

const filteredPets = computed(() => {
  const search = searchTerm.value.toLowerCase();
  return props.pets.filter(pet => {
    const matchesSearch = [pet.name, pet.species, pet.breed]
      .some(f => (f||'').toLowerCase().includes(search));
    const matchesSelection = !props.selectedPetId
      || pet.id == props.selectedPetId;
    return matchesSearch && matchesSelection;
  });
});



const formatDob = (pet) => {
  if (pet.dob) return pet.dob
  const day = pet.dob_day || ''
  const month = pet.dob_month || ''
  const year = pet.dob_year || ''
  return [day, month, year].filter(Boolean).join('/') || '-'
}
</script>

<style scoped>
.search-container input.form-control {
  width: 100%;
  max-width: 32rem;
  border-radius: 2rem;
  padding: 0.6rem 1.2rem;
  border: 1px solid #ccc;
}
</style>
