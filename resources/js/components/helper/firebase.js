import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import toastService from "./toastService";
import { notifikasi_state } from "../stores/notifikasi_state";
import { dekripsi } from "./crypt";
import { cart_state } from "../stores/cart_state";
import { saldo_state } from "../stores/saldo_state";

const firebaseConfig = {
    apiKey: "AIzaSyAAnX5nJ0uhG8d9u-xxg9mgPHM530j_0nk",
    authDomain: "narindashop-71cdd.firebaseapp.com",
    projectId: "narindashop-71cdd",
    storageBucket: "narindashop-71cdd.firebasestorage.app",
    messagingSenderId: "284341337193",
    appId: "1:284341337193:web:40a44c5de159b42744f6f8",
};

// Inisialisasi Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Minta izin notifikasi
export const requestPermission = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const token = await getToken(messaging, {
                vapidKey:
                    "BPB8k7WRs7KfQ9PvoH2hLffAIo_gw6UCjrpFgRGfwhFAx7Jk1Yqi89QUX6YWNiRhx93E4VM_U04Sc0q-2kHBdO8",
            });
            localStorage.setItem("gfrb", token);
            return token;
        } else {
            toastService.show(
                "Perhatian",
                "Notifikasi dinonaktifkan. Anda tidak akan menerima pemberitahuan atau informasi terbaru. Harap aktifkan kembali notifikasi.",
                "warning"
            );
        }
    } catch (err) {
        console.error("Gagal mendapatkan token:", err);
    }
};

// Terima notifikasi saat app aktif
onMessage(messaging, (payload) => {
    toastService.show(
        payload.notification.title,
        payload.notification.body,
        "success"
    );
    notifikasi_state.state.payloadNotif = payload;

    try {
                const t = localStorage.getItem("t");
                const u = localStorage.getItem("u");
                if (t && u) {
                    let userDetail = JSON.parse(
                        dekripsi(u)
                    );
                    if (userDetail) {
                        this.role = userDetail.role_akun;
                        cart_state.dispatch("listCart", []);
                        saldo_state.dispatch("viewSaldo", []);
                    } else {

                    }
                }
            } catch (error) {

            }
});
