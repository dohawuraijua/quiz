// requestManager.js
import Api from "./Api";
import toastService from "./toastService";
import { login_state } from "../../stores/login_state";

const RequestManager = async (url_path, params, config = null) => {
    try {
        const response = await Api.post(url_path, params, config);

        if (response.status == 200 || response.status == 201) {
            return response;
        } else {
            failedAlert(
                "error",
                response.data.message + "kode:" + response.status
            );
            return Promise.reject("Unauthorized");
        }
    } catch (error) {
        let errorMessage = "Terjadi kesalahan. Silakan coba lagi nanti.";

        if (error.response) {
            // Check if the response status is 401 and show custom message
            if (error.response.status === 401) {
                localStorage.clear();

                errorMessage = "Sesi login anda berakhr, silahkan login ulang.";
                // toastService.show("Peringatan", errorMessage, "warning");
                const next = encodeURIComponent(window.location.pathname + window.location.search);
                // setTimeout(() => {
                //     window.location = `/?next=${next}`;
                // }, 200);
                login_state.commit("setuserDetail", []);
                login_state.commit("setuserRole", []);
            } else {
                // Otherwise, show the message from the API response, if available
                errorMessage =
                    error.response?.data?.message || error.response?.statusText;
                toastService.show("Error", errorMessage, "error");
            }
        }
        throw error;
    }
};

function failedAlert(title, msg, icon = "error") {
    toastService.show(title, msg, icon);
}

export default RequestManager;
