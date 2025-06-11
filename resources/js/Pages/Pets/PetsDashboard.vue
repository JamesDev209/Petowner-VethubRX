<script setup>
import { ref } from "vue";
import { router, Link } from "@inertiajs/vue3";
import PetRegister from "@/Components/PetRegister.vue";
import PetsTable from "@/Components/PetsTable.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

const props = defineProps({
    pets: {
        type: Array,
        default: () => [],
    },
});

const selectedPetId = ref('');

const isSidebarOpen = ref(false); 

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
  isSidebarOpen.value = false;
};

// Modal
const petRegisterRef = ref(null);
const openModal = () => {
  if (petRegisterRef.value?.openModal) {
    petRegisterRef.value.openModal();
  }
};
function handleSelectedPet(id) {
  console.log('seleccionaste pet id:', id);
  selectedPetId.value = id;
}

const logout = () => {
    router.post("/logout");
};
</script>

<template>
  <!-- Sidebar -->
  <Sidebar
  :is-open="isSidebarOpen" 
  @close-sidebar="isSidebarOpen = false" 
   :pets="pets" 
   @add-pet="openModal"
    @pet-selected="handleSelectedPet" />

  <!-- Main Content -->
  <div class="main-content min-vh-100 bg-white d-flex flex-column justify-content-center align-items-center text-center" id="mainContent">
    
    <!-- Header -->
    <div class="header w-100 p-3 h-75 d-flex justify-content-between align-items-center">
      <!-- Hamburger -->
      <div 
        class="hamburger d-md-none text-extra-large bg-blue-dark text-white px-2 py-1 rounded" 
        @click="toggleSidebar">
        &#9776;
      </div>

      <!-- User Dropdown -->
      <div class="dropdown ms-auto">
        <button class="btn btn-custom-rf bg-blue-dark buttons-hover rounded-circle d-flex align-items-center justify-content-center dropdown-toggle"
          type="button"
          id="userDropdown"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <img
            :src="'assets/img/Rf.png'"
            alt="User"
            @error="(e) => { e.target.src = 'assets/img/default-user.png' }"
          />
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
          <li>
            <Link href="/profile" class="dropdown-item">👤 Profile</Link>
          </li>
          <li>
            <Link href="/change-password" class="dropdown-item">🔒 Change password</Link>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <button class="dropdown-item fw-semibold" @click="logout">🚪 Log out</button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Pets Table (if there are pets) -->
    <div v-if="pets.length > 0" class="container my-4">
      <PetsTable
    :pets="props.pets"
    :selected-pet-id="selectedPetId"
    @add-pet="openModal"
  />
    </div>

    <!-- Empty state (no pets) -->
    <div v-if="pets.length === 0" class="flex-grow-1 d-flex flex-column justify-content-center align-items-center w-100 px-4">
      <div class="empty-state py-5 text-center">
        <h2 class="fw-bold text-ligth-blue mb-4">No Pets Added Yet!</h2>
        <img src="assets/img/gatosimg.png" alt="No Pets" class="img-fluid my-3" width="240" />
        <p class="text-muted mb-4 fw-normal">
          Register your pet to access prescriptions, supplements, <br> and personalized recommendations.
        </p>
        <button class="btn rounded-pill bg-blue-dark p-2 px-5 text-white buttons-hover" @click="openModal">
          Add your pet
        </button>
      </div>
    </div>
  </div>

  <!-- Pet Registration Modal -->
  <PetRegister ref="petRegisterRef" />
</template>
