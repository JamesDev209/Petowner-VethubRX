<template>
    <div class="row g-0">
      <div class="col-12 col-md-6">
        <div class="card-one left-card">
          <div class="card-tres medi d-flex justify-content-center align-items-center">
            <div class="col-md-12 text-center">
              <h3 class="text-ligth-blue fw-bold text-extra-large">
                Join VetHub Rx. Simplify Your Pet's Care
              </h3>
              <p class="text-muted px-3 text-small">
                Easy Mana prescriptions, <br> supplements, 
                and health <br> recommendations all in one place            
              </p>
              <div class="d-flex justify-content-center mt-3">
                <img src="" alt="Cute cat illustration" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-sm-6">
        <div class="card d-flex justify-content-center right-card">
          <div class="card-three verify-email bg-blue-dark d-flex justify-content-center flex-column align-items-center">
            <div class="logo mb-5">
              <img src="" alt="VetHub Logo" width="88%">
            </div>
            <h5 class="fw-bold text-white mb-3">Verify your mail</h5>
            <p class="text-white text-small text-center mt-3">
              We've sent a 5-digit code to your email. <br>
              If you don't see it, check your spam folder.                   
            </p>
  
            <form class="d-flex flex-column align-items-center mb-4" @submit.prevent="submitCode">
              <h4 class="fw-bold text-white text-large mb-2">Code</h4>
              <div class="d-flex">
                <input v-for="(digit, index) in code" :key="index" v-model="code[index]" type="text" maxlength="1" class="code-input" />
              </div>
              <p class="text-white mt-3 text-small text-center">
                Didn't receive the code? <a href="#" class="text-white fw-bold" @click.prevent="resendCode">Resend</a>
              </p>
            </form>
  
            <button class="verify-btn fw-semibold mt-3 rounded-5 py-2 text-dark-blue w-100" @click="submitCode">
              Verify
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const code = ref(["", "", "", "", ""])
  
  const submitCode = () => {
    const fullCode = code.value.join('')
    console.log("Código ingresado:", fullCode)
    // Aquí puedes hacer la llamada a tu backend para verificar el código
  }
  
  const resendCode = () => {
    console.log("Resend code triggered")
    // Aquí iría la lógica para reenviar el código al correo
  }
  </script>
  
  <style scoped>
  /* Aquí puedes incluir las clases como .code-input o .verify-btn si las tenías en tu CSS */
  </style>
  