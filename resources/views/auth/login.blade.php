<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sakura</title>
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('loginform/style.css') }}" />
</head>
<body>

    <!-- Image Background -->
    <div class="image-background"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="fas fa-cherry-blossom"></i> PT SAKURA JAVA INDONESIA</h2>
                <p>Welcome back! Please login to your account</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center;">
                        <i class="fas fa-exclamation-circle"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Success Message (jika ada) -->
                @if (session('status'))
                    <div class="success-message" style="display: block;">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email / Plate Field -->
                    <div class="form-group">
                        <label for="login" class="form-label">Email atau Plat Nomor</label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input
                                id="login"
                                type="text"
                                class="form-control-custom @error('login') is-invalid @enderror"
                                name="login"
                                value="{{ old('login') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email atau plat nomor"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group-custom">
                            <input
                                id="password"
                                type="password"
                                class="form-control-custom @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            />
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        />
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                <i class="fas fa-question-circle"></i> Forgot Your Password?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('loginform/script.js') }}"></script>

</body>
</html>

