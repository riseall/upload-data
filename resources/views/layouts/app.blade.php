<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data METD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/admin.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('partial.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('partial.topbar')
                @yield('content')
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>2025 &copy; PT. Phapros, Tbk. - All rights reserved.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <!-- Ganti bagian script di bawah -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="js/admin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('access_token');
            const userNameFromStorage = localStorage.getItem('user_name');

            const userNameDisplayTopbar = document.getElementById('user-name-display-topbar');
            const homeUserNameDisplay = document.getElementById('home-user-name');

            const currentPath = window.location.pathname;
            const protectedRoutes = ['/home', '/upload', '/data']; // Sesuaikan rute yang dilindungi

            // Logika redirect jika tidak terotentikasi
            if (!token && protectedRoutes.includes(currentPath)) {
                window.location.href = '/login';
                return;
            }

            // Logika redirect jika sudah terotentikasi dan berada di halaman login/root
            if (token && (currentPath === '/login' || currentPath === '/')) {
                window.location.href = '/home';
                return;
            }

            function updateAllUserNameDisplays(name) {
                if (userNameDisplayTopbar) {
                    userNameDisplayTopbar.textContent = name;
                }
                if (homeUserNameDisplay) {
                    homeUserNameDisplay.textContent = name;
                }
            }

            // Fungsi utama untuk menampilkan nama pengguna
            async function displayUserName() {
                if (userNameFromStorage) {
                    updateAllUserNameDisplays(userNameFromStorage);
                } else if (token) {
                    try {
                        const response = await axios.get(
                            'http://csv-uploader.test/api/user', { // Ganti URL backend Anda
                                headers: {
                                    'Authorization': `Bearer ${token}`
                                }
                            });

                        const userData = response.data;

                        if (userData && userData.name) {
                            updateAllUserNameDisplays(userData.name);
                            localStorage.setItem('user_name', userData.name); // Simpan di localStorage
                        } else {
                            updateAllUserNameDisplays('User'); // Fallback jika nama tidak ada di respons
                            console.warn('Nama pengguna tidak ditemukan dalam respons API.');
                        }
                    } catch (error) {
                        console.error('Kesalahan saat mengambil data user:', error);
                        if (error.response && error.response.status === 401) {
                            console.error('Token tidak valid atau kadaluarsa. Silakan login kembali.');
                            localStorage.removeItem('access_token');
                            localStorage.removeItem('user_name');
                            window.location.href = '/login';
                        } else {
                            updateAllUserNameDisplays('Error Jaringan'); // Fallback error
                        }
                    }
                } else {
                    updateAllUserNameDisplays('Guest');
                }
            }

            displayUserName();
        });
    </script>

    @stack('scripts')
</body>

</html>
