{{-- Modal konfirmasi hapus + pelindung form. Dipasang sekali di setiap layout. --}}
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4"
     role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-xl">
        <h3 id="confirm-title" class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
        <p id="confirm-message" class="mt-2 text-sm text-gray-700"></p>
        <p class="mt-1 text-xs text-gray-500">Data yang dihapus tidak akan tampil lagi di sistem.</p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="confirm-cancel"
                    class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Batal</button>
            <button type="button" id="confirm-ok"
                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('confirm-modal');
        var message = document.getElementById('confirm-message');
        var okBtn = document.getElementById('confirm-ok');
        var cancelBtn = document.getElementById('confirm-cancel');
        var pendingForm = null;

        function openModal(form) {
            pendingForm = form;
            message.textContent = form.getAttribute('data-confirm');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            cancelBtn.focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingForm = null;
        }

        // 1) Form dengan atribut data-confirm menampilkan modal sebelum dikirim
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (form.matches && form.matches('form[data-confirm]')) {
                e.preventDefault();
                openModal(form);
            }
        });

        okBtn.addEventListener('click', function () {
            var form = pendingForm;
            closeModal();
            if (form) {
                form.submit();
            }
        });
        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && pendingForm) {
                closeModal();
            }
        });

        // 2) Cegah data ganda: tombol simpan dinonaktifkan setelah form dikirim
        function setBusy(form, busy) {
            form.querySelectorAll('button[type="submit"]').forEach(function (btn) {
                btn.disabled = busy;
                btn.classList.toggle('opacity-60', busy);
                btn.classList.toggle('cursor-not-allowed', busy);
            });
        }

        document.addEventListener('submit', function (e) {
            if (e.defaultPrevented) {
                return;
            }
            var form = e.target;
            if (form.method && form.method.toLowerCase() === 'post') {
                setBusy(form, true);
            }
        });

        // Tombol "Kembali" browser: aktifkan lagi tombol yang sempat dinonaktifkan
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                document.querySelectorAll('form').forEach(function (form) {
                    setBusy(form, false);
                });
            }
        });

        // 3) Jika ada pesan error validasi, fokus ke field yang bermasalah
        document.addEventListener('DOMContentLoaded', function () {
            var error = document.querySelector('form ul.text-red-600');
            if (error && error.parentElement) {
                var field = error.parentElement.querySelector('input:not([type="hidden"]), select, textarea');
                if (field) {
                    field.focus();
                }
            }
        });
    })();
</script>
