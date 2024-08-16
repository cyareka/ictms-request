<template>
    <div>
      <h1>Verify OTP</h1>
  
      <form @submit.prevent="submit">
        <div>
          <label for="otp">Enter OTP</label>
          <input id="otp" v-model="form.otp" type="text" required>
        </div>
  
        <div>
          <button type="submit">Verify</button>
        </div>
      </form>
  
      <div v-if="form.errors.otp">
        <p>{{ form.errors.otp }}</p>
      </div>
  
      <button @click="resendOtp" :disabled="isResending">
        Resend OTP
      </button>
  
      <div v-if="message">
        <p>{{ message }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import { useForm } from '@inertiajs/inertia-vue3';
  import { ref } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const form = useForm({
        otp: '',
      });
  
      const message = ref('');
      const isResending = ref(false);
  
      function submit() {
        form.post(route('verify.otp'));
      }
  
      async function resendOtp() {
        try {
          isResending.value = true;
          message.value = '';
          
          const response = await axios.post(route('resend.otp'));
          
          message.value = response.data.message;
        } catch (error) {
          message.value = 'Failed to resend OTP. Please try again.';
        } finally {
          isResending.value = false;
        }
      }
  
      return {
        form,
        message,
        isResending,
        submit,
        resendOtp,
      };
    },
  };
  </script>
  