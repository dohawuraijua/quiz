<template>
    <span>
        {{ formattedTanggal }}
    </span>
</template>

<script>
export default {
    name: 'TanggalIndo',
    props: {
        tanggal: {
            type: [String, Number, null],
            required: false,
            default: null
        },
        time: {
            type: Boolean,
            default: false
        },
        time_only: {   // ⬅️ Tambahan baru
            type: Boolean,
            default: false
        },
        is_unix: {
            type: Boolean,
            default: false
        },
        longdate: {
            type: Boolean,
            default: true
        },
        daya: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        formattedTanggal() {
            if (!this.tanggal) return '';

            let date = this.is_unix
                ? new Date(this.tanggal * 1000)
                : new Date(this.tanggal);

            if (isNaN(date.getTime())) return '';

            const days = [
                "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
            ];
            const months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const monthsShort = [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
            ];

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            // ✅ Jika hanya ingin tampil waktu saja
            if (this.time_only) {
                return `${hours}:${minutes}`;
            }

            const dayName = days[date.getDay()];
            const day = String(date.getDate()).padStart(2, '0');
            const month = this.longdate
                ? months[date.getMonth()]
                : monthsShort[date.getMonth()];
            const year = date.getFullYear();

            let formattedDate = `${day} ${month} ${year}`;

            if (this.daya) {
                formattedDate = `${dayName}, ${formattedDate}`;
            }

            if (this.time) {
                formattedDate += ` ${hours}:${minutes}:${seconds}`;
            }

            return formattedDate;
        }
    }
}
</script>