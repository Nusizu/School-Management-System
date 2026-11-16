// Notifikasi sukses hilang otomatis setelah 4 detik
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert-dismissible').forEach(function (el) {
        setTimeout(function () {
            el.classList.add('is-hidden');
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });
});