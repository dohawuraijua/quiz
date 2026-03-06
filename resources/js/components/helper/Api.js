import axios from "axios";
import { dekripsi, enkripsi } from "./crypt";
import { loadingState } from "../../stores/loading.js";
import { API_HOST } from "./const_string.js";

const Api = axios.create({
    baseURL: API_HOST,
    headers: {
        "Content-Type": "application/json",
    },
});

const simulateProgress = () => {
    if (loadingState.progress < 90) {
        loadingState.progress += Math.random() * 10; // Increment randomly
        setTimeout(simulateProgress, 200);
    }
};

Api.interceptors.request.use(
    async (config) => {
        loadingState.isLoading = true;
        loadingState.progress = 0;
        simulateProgress();
        //PENTING UNTUK BACKEND
        //1. TOKEN
        //2. ENCODING
        //3. ROLES

        if (config.url.includes("token_for_api")) {
            config.headers["Content-Type"] = "application/json";
            return config;
        }

        try {
            let BearerToken = "";
            let userDetail = [];
            if (localStorage.getItem("t") && localStorage.getItem("u")) {
                BearerToken = dekripsi(localStorage.getItem("t"));
                userDetail = JSON.parse(dekripsi(localStorage.getItem("u")));
            }
            if (config.method === "post" && config.data instanceof FormData) {
                config.headers["Content-Type"] = "multipart/form-data";
                if (userDetail["id"]) {
                    config.headers["Authorization"] = "Bearer " + BearerToken;
                    // config.data.append("userid", userDetail["id"]);
                }
            } else {
                config.headers["Content-Type"] = "application/json";
                if (userDetail["id"]) {
                    config.headers.Authorization = "Bearer " + BearerToken;
                    // config.data.userid = userDetail["id"];
                }
            }
        } catch (error) {
            config.headers["Content-Type"] = "application/json";
            // return Promise.reject("Akses tidak valid");
        }

        return config;
    },
    (error) => {
        // Do something with request error
        loadingState.isLoading = false;
        loadingState.progress = 100;
        return handleError(error);
    }
);

const MAX_RETRIES = 3;
const RETRY_DELAYS = [2000, 4000, 8000];

Api.interceptors.response.use(
    (response) => {
        loadingState.progress = 100; // Set to 100% on response
        setTimeout(() => {
            loadingState.isLoading = false;
            loadingState.progress = 0; // Reset after a delay
        }, 300); // Adjust delay as needed
        return response;
    },
    async (error) => {
        loadingState.progress = 100; // Set to 100% on error
        setTimeout(() => {
            loadingState.isLoading = false;
            loadingState.progress = 0; // Reset after a delay
        }, 300);

        // Cek kalau ada response dari server
        if (error.response.status == 401) {
            // localStorage.clear();
            // window.location = "/logout";
        }

        return Promise.reject(error);
    }
);
export default Api;
