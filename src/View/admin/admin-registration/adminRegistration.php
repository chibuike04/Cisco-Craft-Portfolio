<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="<?= e($csrfToken) ?>"
    >

    <meta
        name="app-url"
        content="<?= e($appUrl) ?>"
    >

    <title>My Portfolio Dashboard Registration | Authentication</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <!-- Admin Registration CSS -->
    <link
        rel="stylesheet"
        href="<?= e(url('/assets/css/adminRegistration.css')) ?>"
    >

</head>

<body data-initial-view="<?= e($activeView) ?>">

<main class="auth-shell">

    <!-- =================================
         REGISTRATION PAGE
    ================================== -->
    <section class="auth-view" id="registerView">

        <div class="auth-card">

            <aside class="welcome-panel">

                <span class="shape shape-one"></span>
                <span class="shape shape-two"></span>
                <span class="shape shape-three"></span>
                <span class="shape shape-four"></span>

                <div class="welcome-content">

                    <h2>Welcome Back!</h2>

                    <p>
                        To keep connected with us please login
                        with your personal info.
                    </p>

                    <button class="outline-btn" type="button" data-show="login">
                        SIGN IN
                    </button>

                </div>

            </aside>

            <div class="form-panel">

                <div class="form-content">

                    <h1>Create Account</h1>

                    <div class="social-row">

                        <a class="social-btn" href="#" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a class="social-btn" href="#" aria-label="Google">
                            G+
                        </a>

                        <a class="social-btn" href="#" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>

                    </div>

                    <p class="form-hint">
                        or use your email for registration:
                    </p>

                    <div class="status-message" id="registerStatus" role="status"></div>

                    <form id="registerForm">

                        <div class="input-wrap">

                            <i class="fa-regular fa-user"></i>

                            <input
                                type="text"
                                name="name"
                                placeholder="Name"
                                autocomplete="name"
                                required
                            >

                        </div>

                        <div class="input-wrap">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="Email"
                                autocomplete="email"
                                required
                            >

                        </div>

                        <div class="input-wrap">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                id="registerPassword"
                                type="password"
                                name="password"
                                placeholder="Password"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <button
                                class="toggle-password"
                                type="button"
                                data-toggle="registerPassword"
                                aria-label="Show password"
                            >
                                <i class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                        <button class="primary-btn" type="submit">
                            SIGN UP
                        </button>

                    </form>

                    <p class="bottom-note">
                        Already have an account?

                        <a class="text-link" href="#" data-show="login">
                            Sign In
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =================================
         LOGIN PAGE
    ================================== -->
    <section class="auth-view" id="loginView" hidden>

        <div class="auth-card">

            <aside class="welcome-panel">

                <span class="shape shape-one"></span>
                <span class="shape shape-two"></span>
                <span class="shape shape-three"></span>
                <span class="shape shape-four"></span>

                <div class="welcome-content">

                    <h2>Welcome Back!</h2>

                    <p>
                        Sign in to access your account
                        and continue your journey.
                    </p>

                    <button class="outline-btn" type="button" data-show="register">
                        CREATE ACCOUNT
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </div>

            </aside>

            <div class="form-panel">

                <div class="form-content">

                    <h1>Login to Your Account</h1>

                    <p class="subtitle">
                        Enter your credentials to continue
                    </p>

                    <div class="status-message" id="loginStatus" role="status"></div>

                    <form id="loginForm">

                        <div class="input-wrap">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="Email Address"
                                autocomplete="email"
                                required
                            >

                        </div>

                        <div class="input-wrap">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                id="loginPassword"
                                type="password"
                                name="password"
                                placeholder="Password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                class="toggle-password"
                                type="button"
                                data-toggle="loginPassword"
                                aria-label="Show password"
                            >
                                <i class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                        <div class="form-options">

                            <label class="remember">
                                <input type="checkbox" name="remember">
                                Remember me
                            </label>

                            <a class="text-link" href="#" data-show="forgot">
                                Forgot Password?
                            </a>

                        </div>

                        <button class="primary-btn" type="submit">
                            LOGIN
                        </button>

                    </form>

                    <div class="divider">
                        OR
                    </div>

                    <div class="social-row">

                        <a class="social-btn" href="#" aria-label="Google">
                            G+
                        </a>

                        <a class="social-btn" href="#" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a class="social-btn" href="#" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>

                    </div>

                    <p class="bottom-note">
                        Don't have an account?

                        <a class="text-link" href="#" data-show="register">
                            Sign Up
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =================================
         FORGOT PASSWORD PAGE
    ================================== -->
    <section class="auth-view" id="forgotView" hidden>

        <div class="auth-card">

            <aside class="welcome-panel">

                <span class="shape shape-one"></span>
                <span class="shape shape-two"></span>
                <span class="shape shape-three"></span>
                <span class="shape shape-four"></span>

                <div class="welcome-content">

                    <h2>Forgot Password?</h2>

                    <p>
                        Don't worry! It happens to the best of us.
                        Enter your email and we'll send you reset instructions.
                    </p>

                    <button class="outline-btn" type="button" data-show="login">
                        <i class="fa-solid fa-arrow-left"></i>
                        BACK TO LOGIN
                    </button>

                </div>

            </aside>

            <div class="form-panel">

                <div class="form-content">

                    <h1>Reset Your Password</h1>

                    <p class="subtitle">
                        Enter your email address and we'll send you
                        a link to reset your password.
                    </p>

                    <div class="status-message" id="forgotStatus" role="status"></div>

                    <form id="forgotForm">

                        <div class="input-wrap">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="Email Address"
                                autocomplete="email"
                                required
                            >

                        </div>

                        <button class="primary-btn" type="submit">
                            SEND RESET LINK
                        </button>

                    </form>

                    <div class="divider">
                        OR
                    </div>

                    <p class="bottom-note">
                        Remember your password?

                        <a class="text-link" href="#" data-show="login">
                            Login
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =================================
         RESET PASSWORD PAGE
    ================================== -->
    <section class="auth-view" id="resetView" hidden>

        <div class="auth-card">

            <aside class="welcome-panel">

                <span class="shape shape-one"></span>
                <span class="shape shape-two"></span>
                <span class="shape shape-three"></span>
                <span class="shape shape-four"></span>

                <div class="welcome-content">

                    <h2>Create New Password!</h2>

                    <p>
                        You're almost there! Choose a strong new password
                        to secure your account and get back to your journey.
                    </p>

                    <button class="outline-btn" type="button" data-show="login">
                        <i class="fa-solid fa-arrow-left"></i>
                        BACK TO LOGIN
                    </button>

                </div>

            </aside>

            <div class="form-panel">

                <div class="form-content">

                    <h1>Reset Password</h1>

                    <p class="subtitle">
                        Create a new password for your account.
                        Make sure it's strong and secure.
                    </p>

                    <div
                        class="status-message"
                        id="resetStatus"
                        role="status"
                        aria-live="polite">
                    </div>

                    <form id="resetForm" class="reset-form">

                        <!-- NEW PASSWORD -->
                        <div class="input-wrap">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="newPassword"
                                name="password"
                                placeholder="New Password"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <button
                                class="toggle-password"
                                type="button"
                                data-toggle="newPassword"
                                aria-label="Show password">

                                <i class="fa-regular fa-eye"></i>

                            </button>

                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="input-wrap">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="confirmPassword"
                                name="confirm_password"
                                placeholder="Confirm New Password"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <button
                                class="toggle-password"
                                type="button"
                                data-toggle="confirmPassword"
                                aria-label="Show password">

                                <i class="fa-regular fa-eye"></i>

                            </button>

                        </div>

                        <!-- PASSWORD REQUIREMENTS -->
                        <div class="password-requirements">

                            <h4>Password Requirements</h4>

                            <p>
                                <i class="fa-solid fa-circle-check"></i>
                                At least 8 characters
                            </p>

                            <p>
                                <i class="fa-solid fa-circle-check"></i>
                                Use uppercase and lowercase letters
                            </p>

                            <p>
                                <i class="fa-solid fa-circle-check"></i>
                                Include at least one number
                            </p>

                        </div>

                        <button class="primary-btn" type="submit">
                            RESET PASSWORD
                        </button>

                    </form>

                    <p class="reset-bottom">
                        Remember your password?

                        <a href="#" data-show="login">
                            Login
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =================================
     JAVASCRIPT
================================== -->
<script
    src="<?= e(url('/assets/js/adminRegistration.js')) ?>"
    defer>
</script>

</body>
</html>
