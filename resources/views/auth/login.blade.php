@extends('layouts.guest')

@section('content')
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                    </div>

                    <div id="global-error-messages" class="alert alert-danger alert-dismissible fade show" role="alert"
                        style="display: none;">
                        <span id="error-text"></span>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                    </div>

                    <form id="login-form" class="user" method="POST" {{-- action="{{ route('login') }}" --}}>
                        <div class="form-group">
                            <input id="email" type="email" class="form-control form-control-user" name="email"
                                required autocomplete="email" autofocus placeholder="Email Address">
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" class="form-control form-control-user" name="password"
                                required autocomplete="current-password" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Login
                        </button>
                    </form>
                    <hr>
                    {{-- <div class="text-center">
                        @if (Route::has('password.request'))
                            <a class="small" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div> --}}
                    {{-- <div class="text-center">
                        <a class="small" href="{{ route('register') }}">Create an Account!</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const globalErrorDiv = document.getElementById('global-error-messages');
            const errorTextSpan = document.getElementById(
                'error-text'); // Dapatkan elemen span untuk teks error

            // Clear previous errors and remove 'is-invalid' class
            if (emailInput && emailInput.classList) emailInput.classList.remove('is-invalid');
            if (passwordInput && passwordInput.classList) passwordInput.classList.remove('is-invalid');

            // Sembunyikan div error global dan kosongkan isinya
            if (globalErrorDiv) globalErrorDiv.style.display = 'none';
            if (errorTextSpan) errorTextSpan.innerHTML = ''; // Gunakan innerHTML untuk break line

            const email = emailInput ? emailInput.value : '';
            const password = passwordInput ? passwordInput.value : '';

            try {
                const response = await axios.post(
                    'http://csv-uploader.test/api/login', { // Ganti dengan URL backend Anda
                        email: email,
                        password: password
                    });

                const data = response.data;

                // console.log('Login successful:', data);
                localStorage.setItem('access_token', data.access_token);
                if (data.user && data.user.name) {
                    localStorage.setItem('user_name', data.user.name);
                }
                window.location.href = '/home'; // Redirect langsung tanpa alert sukses

            } catch (error) {
                // console.error('Login failed:', error);
                const data = error.response ? error.response.data : null;
                let errorMessage = '';

                if (data && data.errors) {
                    // Kumpulkan semua pesan error validasi ke dalam satu string
                    for (const field in data.errors) {
                        if (data.errors.hasOwnProperty(field)) {
                            data.errors[field].forEach(message => {
                                errorMessage += message + '<br>'; // Tambahkan break line
                            });
                            // Tambahkan kelas is-invalid ke input yang relevan
                            const inputElement = document.getElementById(field);
                            if (inputElement && inputElement.classList) {
                                inputElement.classList.add('is-invalid');
                            }
                        }
                    }
                } else if (data && data.message) {
                    errorMessage = data.message; // Pesan error umum dari API
                } else {
                    errorMessage =
                        'Terjadi kesalahan saat mencoba login. Silakan coba lagi.'; // Error jaringan/tidak terduga
                }

                // Tampilkan pesan error di div global
                if (globalErrorDiv && errorTextSpan) {
                    errorTextSpan.innerHTML = errorMessage;
                    globalErrorDiv.style.display = 'block';
                } else {
                    // Fallback alert jika div global tidak ditemukan
                    alert(errorMessage.replace(/<br>/g, '\n')); // Ganti <br> dengan newline untuk alert
                }
            }
        });
    </script>
@endsection
