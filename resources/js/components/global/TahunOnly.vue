<template>
    <span>
        {{ formattedTanggal }}
    </span>
</template>

<script>
export default {
    name: 'TahunOnly',
    props: {
        tanggal: {
            type: [String, Number, null],
            required: false,
            default: null
        },
        withmonth: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        formattedTanggal() {
            if (!this.tanggal) {
                return '';
            }

            let date;
            if (this.is_unix) {
                date = new Date(this.tanggal * 1000); // Convert UNIX timestamp
            } else {
                date = new Date(this.tanggal);
            }

            if (isNaN(date.getTime())) {
                return '';
            }

            const months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const monthsShort = [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
            ];

            const day = String(date.getDate()).padStart(2, '0');
            const month = this.longdate ? months[date.getMonth()] : monthsShort[date.getMonth()];
            const year = date.getFullYear();

            if (this.withmonth) {
                return `${month} ${year}`;
            } else {
                return `${year}`;
            }
        }
    }
}
</script>
