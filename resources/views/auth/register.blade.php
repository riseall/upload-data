@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>
                    <form id="register-form" class="user" method="POST" {{-- action="{{ route('register') }}" --}}>

                        <div class="form-group">
                            <input id="name" type="text"
                                class="form-control form-control-user @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama">

                            @error('name')
                                <span id="name-error" class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="email" type="email"
                                class="form-control form-control-user @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                            @error('email')
                                <span id="email-error" class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input id="password" type="password"
                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password" placeholder="Password">

                                @error('password')
                                    <span id="password-error" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <input id="password-confirm" type="password" class="form-control form-control-user"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm Password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Register
                        </button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('register-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password-confirm').value;

            // Ambil elemen error span
            const nameErrorSpan = document.getElementById('name-error');
            const emailErrorSpan = document.getElementById('email-error');
            const passwordErrorSpan = document.getElementById('password-error');

            // Bersihkan error sebelumnya, dengan pengecekan null
            if (nameErrorSpan) {
                nameErrorSpan.style.display = 'none';
                nameErrorSpan.querySelector('strong').textContent = '';
            }
            if (emailErrorSpan) {
                emailErrorSpan.style.display = 'none';
                emailErrorSpan.querySelector('strong').textContent = '';
            }
            if (passwordErrorSpan) {
                passwordErrorSpan.style.display = 'none';
                passwordErrorSpan.querySelector('strong').textContent = '';
            }

            try {
                const response = await axios.post(
                    'https://kacaerp.phapros.co.id/apimetd/api/register', { // Ganti dengan URL backend Anda
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    }
                );

                const data = response.data; // Axios otomatis mengembalikan data JSON di response.data

                // console.log('Register berhasil:', data);
                localStorage.setItem('access_token', data.access_token);
                if (data.user && data.user.name) {
                    localStorage.setItem('user_name', data.user.name);
                }
                window.location.href = '/metd/home';

            } catch (error) {
                console.error('Register gagal:', error);
                const data = error.response ? error.response.data : null; // Ambil data error dari respons Axios

                if (data && data.errors) {
                    if (data.errors.name && nameErrorSpan) {
                        nameErrorSpan.style.display = 'block';
                        nameErrorSpan.querySelector('strong').textContent = data.errors.name[0];
                    }
                    if (data.errors.email && emailErrorSpan) {
                        emailErrorSpan.style.display = 'block';
                        emailErrorSpan.querySelector('strong').textContent = data.errors.email[0];
                    }
                    if (data.errors.password && passwordErrorSpan) {
                        passwordErrorSpan.style.display = 'block';
                        passwordErrorSpan.querySelector('strong').textContent = data.errors.password[0];
                    }
                } else if (data && data.message) {
                    alert(data.message);
                } else {
                    alert('Terjadi kesalahan saat mencoba Register. Silakan coba lagi.');
                }
            }
        });
    </script>
@endsection
