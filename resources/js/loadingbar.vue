<template>
    <div v-if="isLoading" class="progress-bar-container">
      <div class="progress-bar" :style="{ width: progress + '%' }"></div>
    </div>
  </template>
  
  <script>
  import { loadingState } from "./stores/loading.js";
  
  export default {
    computed: {
      isLoading() {
        return loadingState.isLoading;
      },
      progress() {
        return loadingState.progress;
      },
    },
  };
  </script>
  
  <style>
  .progress-bar-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: rgba(60, 106, 235, 0.9); /* Optional background */
    z-index: 1000;
  }
  
  .progress-bar {
    height: 100%;
    background-color: #ffffff; /* Customize color */
    transition: width 0.2s ease;
  }
  </style>
  