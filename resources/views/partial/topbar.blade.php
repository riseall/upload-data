<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-lg-inline text-gray-600 small" id="user-name-display-topbar">Guest</span>
                <i class="fas fa-angle-down"></i>
                {{-- <img class="img-profile rounded-circle" src="img/undraw_profile.svg"> --}}
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                {{-- <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a> --}}
                {{-- <div class="dropdown-divider"></div> --}}
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>

</nav>
<!-- End of Topbar -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih tombol "Logout" dibawah jika anda sudah siap untuk keluar sesi anda.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirm-logout-button">Logout</button>
                {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form> --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal Peringatan Inaktivitas (untuk auto-logout) -->
<div class="modal fade" id="idleWarningModal" tabindex="-1" aria-labelledby="idleWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="idleWarningModalLabel">Peringatan Sesi Berakhir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda tidak aktif selama beberapa waktu. Sesi Anda akan segera berakhir.
                Apakah Anda ingin tetap login?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="resetIdleTimer()">Tetap Login</button>
                <button type="button" class="btn btn-danger" onclick="logoutUser()">Logout Sekarang</button>
            </div>
        </div>
    </div>
</div>
<script>
    const IDLE_TIMEOUT = 1800000; // 30 menit
    const WARNING_TIMEOUT = 30000; // 30 detik

    let idleTimer;
    let warningTimer;
    let logoutInitiated = false;

    // Fungsi untuk mereset timer aktivitas
    function resetIdleTimer() {
        clearTimeout(idleTimer);
        clearTimeout(warningTimer);
        logoutInitiated = false; // Reset status logout
        hideWarningModal(); // Sembunyikan modal jika terlihat
        idleTimer = setTimeout(showWarning, IDLE_TIMEOUT - WARNING_TIMEOUT); // Set timer untuk menampilkan peringatan
    }

    // Fungsi untuk menampilkan modal peringatan
    function showWarning() {
        // Pastikan Bootstrap JS sudah dimuat sebelum mencoba menggunakannya
        if (typeof bootstrap !== 'undefined') {
            const warningModal = new bootstrap.Modal(document.getElementById('idleWarningModal'));
            warningModal.show();
        } else {
            console.warn('Bootstrap JS tidak dimuat. Modal peringatan inaktivitas tidak dapat ditampilkan.');
            // Fallback: langsung logout jika modal tidak bisa ditampilkan
            logoutUser();
            return;
        }

        // Set timer untuk logout paksa setelah peringatan
        warningTimer = setTimeout(logoutUser, WARNING_TIMEOUT);
    }

    // Fungsi untuk logout pengguna (disesuaikan agar memanggil API backend menggunakan Axios)
    async function logoutUser() {
        if (logoutInitiated) return; // Mencegah logout ganda
        logoutInitiated = true;

        const currentToken = localStorage.getItem('access_token');

        // Tutup modal manual jika dibuka dengan jQuery/Bootstrap 4 JS
        if (typeof $ !== 'undefined' && $('#logoutModal').length) {
            $('#logoutModal').modal('hide');
        }
        // Tutup modal peringatan inaktivitas jika dibuka dengan Bootstrap 5 JS
        if (typeof bootstrap !== 'undefined') {
            const warningModalInstance = bootstrap.Modal.getInstance(document.getElementById('idleWarningModal'));
            if (warningModalInstance) {
                warningModalInstance.hide();
            }
        }


        if (!currentToken) {
            console.log('Tidak ada token untuk logout. Mengarahkan ke halaman login.');
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_name');
            window.location.href = '/login'; // Pastikan ini sesuai dengan rute login Anda
            return;
        }

        try {
            const response = await axios.post(
                'http://csv-uploader.test/api/logout', // Ganti dengan URL backend API Anda
                null, { // Mengirim null sebagai body karena logout POST tidak memerlukan data
                    headers: {
                        'Authorization': `Bearer ${currentToken}`
                    }
                });

            console.log('Logout berhasil:', response.data);
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_name'); // Penting: hapus juga nama pengguna
            window.location.href = '/login'; // Pastikan ini sesuai dengan rute login Anda

        } catch (error) {
            console.error('Logout gagal:', error);
            // Meskipun gagal di server, tetap bersihkan token lokal dan redirect
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_name');
            window.location.href = '/login'; // Pastikan ini sesuai dengan rute login Anda
            // alert('Logout gagal di server. Silakan coba lagi.'); // Opsional: tampilkan alert
        }
    }

    // Fungsi untuk menyembunyikan modal peringatan
    function hideWarningModal() {
        if (typeof bootstrap !== 'undefined') {
            const warningModal = bootstrap.Modal.getInstance(document.getElementById('idleWarningModal'));
            if (warningModal) {
                warningModal.hide();
            }
        } else {
            console.warn('Bootstrap JS tidak dimuat. Modal peringatan inaktivitas tidak dapat disembunyikan.');
        }
    }

    // Event listener untuk mendeteksi aktivitas pengguna
    ['keydown', 'click'].forEach(event => {
        document.addEventListener(event, resetIdleTimer, false);
    });

    // Inisialisasi timer saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        resetIdleTimer(); // Mulai timer inaktivitas saat DOM dimuat

        // Event listener untuk tombol logout manual di modal
        const confirmLogoutButton = document.getElementById('confirm-logout-button');
        if (confirmLogoutButton) {
            confirmLogoutButton.addEventListener('click', async function(event) {
                event.preventDefault();
                // Panggil fungsi logoutUser yang sudah terpusat
                logoutUser();
            });
        }
    });
</script>
