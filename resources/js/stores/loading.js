// src/store/loading.js
import { reactive } from "vue";

export const loadingState = reactive({
    progress: 0,
  isLoading: false,
});
