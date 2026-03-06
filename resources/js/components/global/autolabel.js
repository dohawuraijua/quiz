export default {
    mounted(el) {
        const headers = Array.from(el.querySelectorAll('thead th')).map(th =>
            th.textContent.trim()
        );

        el.querySelectorAll('tbody tr').forEach(row => {
            Array.from(row.children).forEach((td, i) => {
                if (headers[i]) {
                    td.setAttribute('data-label', headers[i]);
                }
            });
        });
    }
};
