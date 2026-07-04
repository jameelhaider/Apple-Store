<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Shop - Login</title>

    <!-- Favicon -->
    <!-- Replace c2.png if it contains Apple branding -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/c2.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet"
        href="{{ asset('newdash/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet"
        href="{{ asset('newdash/assets/vendor/css/core.css') }}" />

    <link rel="stylesheet"
        href="{{ asset('newdash/assets/vendor/css/theme-default.css') }}" />

    <link rel="stylesheet"
        href="{{ asset('newdash/assets/css/demo.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet"
        href="{{ asset('newdash/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet"
        href="{{ asset('newdash/assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('newdash/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('newdash/assets/js/config.js') }}"></script>

</head>

<body>

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">

            <div class="card">
                <div class="card-body">


                    <!-- Welcome -->
                    <h3 class="mb-2 text-center">
                        Welcome to Your Store 👋
                    </h3>

                    <!-- Disclaimer -->
                    <div class="alert alert-warning mb-4">

                        <strong>Notice:</strong>

                        This website is an independent platform and is not
                        affiliated with, endorsed by, or operated by Apple Inc.
                        Any trademarks or brand names belong to their respective owners.

                    </div>

                    <!-- Login Form -->
                    <form id="formAuthentication"
                        class="mb-3"
                        action="{{ route('login') }}"
                        method="POST">

                        @csrf

                        <!-- Email -->
                        <div class="mb-3">

                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus />

                            @error('email')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <!-- Password -->
                        <div class="mb-3 form-password-toggle">

                            <div class="d-flex justify-content-between">

                                <label class="form-label" for="password">
                                    Password
                                </label>

                                <a href="{{ route('password.request') }}">
                                    <small>Forgot Password?</small>
                                </a>

                            </div>

                            <div class="input-group input-group-merge">

                                <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password" />

                                <span class="input-group-text cursor-pointer">
                                    <i class="bx bx-hide"></i>
                                </span>

                            </div>

                            @error('password')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <!-- Remember -->
                        <div class="mb-3">

                            <div class="form-check">

                                <input class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label"
                                    for="remember">

                                    Remember Me

                                </label>

                            </div>

                        </div>

                        <!-- Button -->
                        <div class="mb-3">

                            <button class="btn btn-primary d-grid w-100"
                                type="submit">

                                Sign In

                            </button>

                        </div>

                    </form>

                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">

                <small class="text-muted">

                    © {{ date('Y') }} Shop —
                    Independent platform. Not an official Apple website.

                </small>

            </div>

        </div>
    </div>
</div>

<!-- Core JS -->
<script src="{{ asset('newdash/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('newdash/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('newdash/assets/vendor/js/bootstrap.js') }}"></script>

<script src="{{ asset('newdash/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('newdash/assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('newdash/assets/js/main.js') }}"></script>

</body>
</html>
