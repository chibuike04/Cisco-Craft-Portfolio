
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diprella | Authentication</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --teal: #35b49f;
            --teal-dark: #20a990;
            --ink: #27343b;
            --muted: #849096;
            --field: #f2f6f5;
            --page: #eff5f4;
            --white: #fff;
            --shadow: 0 22px 55px rgba(42, 75, 70, .13);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 42px 24px;
            overflow-x: hidden;
            position: relative;
            background: var(--page);
            color: var(--ink);
            font-family: "Poppins", Arial, sans-serif;
        }

        /* Background shapes */
        body::before {
            content: "";
            position: fixed;
            width: 310px;
            height: 310px;
            right: -155px;
            top: -105px;
            background: #e85b68;
            transform: rotate(42deg);
            z-index: -1;
        }

        body::after {
            content: "";
            position: fixed;
            width: 290px;
            height: 290px;
            left: -130px;
            bottom: -150px;
            border-radius: 50%;
            background: #ffd33f;
            z-index: -1;
        }

        button, input {
            font: inherit;
        }

        button, a {
            -webkit-tap-highlight-color: transparent;
        }

        .auth-shell {
            width: min(100%, 1120px);
            position: relative;
            z-index: 1;
        }

        .auth-card {
            display: grid;
            grid-template-columns: 39% 61%;
            min-height: 650px;
            overflow: hidden;
            border-radius: 15px;
            background: var(--white);
            box-shadow: var(--shadow);
            animation: appear .35s ease both;
        }

        @keyframes appear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* LEFT WELCOME PANEL */
        .welcome-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 56px 38px;
            overflow: hidden;
            text-align: center;
            color: var(--white);
            background: linear-gradient(145deg, #39b69f 0%, #2eb594 100%);
        }

        .brand {
            position: absolute;
            top: 24px;
            left: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: .2px;
            text-decoration: none;
        }

        .brand-mark {
            width: 27px;
            height: 27px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,255,255,.9);
            font-size: 16px;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            max-width: 360px;
        }

        .welcome-content h2 {
            margin-bottom: 15px;
            font-size: clamp(25px, 3vw, 34px);
            line-height: 1.25;
            font-weight: 700;
        }

        .welcome-content p {
            margin: 0 auto 30px;
            max-width: 330px;
            font-size: 14px;
            line-height: 1.9;
            font-weight: 300;
            color: rgba(255,255,255,.96);
        }

        .outline-btn {
            min-width: 190px;
            min-height: 48px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 12px 22px;
            border: 1.5px solid #fff;
            border-radius: 30px;
            background: transparent;
            color: #fff;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .2s ease;
        }

        .outline-btn:hover {
            background: #fff;
            color: var(--teal-dark);
        }

        /* Decorative shapes inside green panel */
        .shape {
            position: absolute;
            background: rgba(255,255,255,.12);
            transform: rotate(45deg);
            pointer-events: none;
        }

        .shape-one {
            width: 25px;
            height: 25px;
            top: 58px;
            right: 70px;
        }

        .shape-two {
            width: 20px;
            height: 20px;
            bottom: 72px;
            right: 58px;
        }

        .shape-three {
            width: 210px;
            height: 210px;
            border-radius: 50%;
            left: -85px;
            bottom: -125px;
            transform: none;
            background: rgba(255,255,255,.10);
        }

        .shape-four {
            width: 70px;
            height: 70px;
            right: -45px;
            top: 52%;
            transform: rotate(18deg);
            background: rgba(255,255,255,.09);
        }

        /* RIGHT FORM PANEL */
        .form-panel {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 55px clamp(30px, 7vw, 95px);
            background: #fff;
        }

        .form-content {
            width: 100%;
            max-width: 490px;
            text-align: center;
        }

        .form-content h1 {
            margin-bottom: 12px;
            color: var(--teal);
            font-size: clamp(27px, 3.2vw, 38px);
            line-height: 1.25;
            font-weight: 700;
        }

        .subtitle {
            margin: 0 auto 30px;
            color: #77838a;
            font-size: 14px;
            line-height: 1.8;
        }

        /* SOCIAL BUTTONS */
        .social-row {
            display: flex;
            justify-content: center;
            gap: 9px;
            margin: 17px 0 25px;
        }

        .social-btn {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border: 1px solid #e3e9e8;
            border-radius: 50%;
            color: #27343b;
            background: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: .2s ease;
        }

        .social-btn:hover {
            border-color: var(--teal);
            color: var(--teal-dark);
            transform: translateY(-2px);
        }

        .form-hint {
            margin-bottom: 14px;
            color: #9aa3a5;
            font-size: 11px;
        }

        /* INPUT FIELDS */
        .input-wrap {
            position: relative;
            margin-bottom: 12px;
        }

        .input-wrap > i {
            position: absolute;
            top: 50%;
            left: 17px;
            transform: translateY(-50%);
            color: #879391;
            font-size: 14px;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            height: 52px;
            padding: 0 48px 0 46px;
            border: 1px solid transparent;
            border-radius: 3px;
            outline: none;
            background: var(--field);
            color: var(--ink);
            font-size: 13px;
            transition: .2s ease;
        }

        .input-wrap input::placeholder {
            color: #8a9495;
        }

        .input-wrap input:focus {
            border-color: rgba(53,180,159,.65);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(53,180,159,.10);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #84908e;
            cursor: pointer;
            padding: 5px;
        }

        /* LOGIN OPTIONS */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 14px 0 23px;
            color: #7f898d;
            font-size: 12px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--teal);
        }

        .text-link {
            color: var(--teal-dark);
            font-weight: 600;
            text-decoration: none;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        /* MAIN BUTTON */
        .primary-btn {
            width: min(100%, 280px);
            min-height: 49px;
            padding: 13px 20px;
            border: 0;
            border-radius: 28px;
            background: linear-gradient(100deg, #37b5a0, #2eb49c);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            cursor: pointer;
            box-shadow: 0 7px 15px rgba(53,180,159,.12);
            transition: .2s ease;
        }

        .primary-btn:hover {
            transform: translateY(-2px);
            filter: brightness(.97);
        }

        .primary-btn:disabled {
            opacity: .65;
            cursor: wait;
            transform: none;
        }

        /* DIVIDER */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 27px 0 18px;
            color: #899397;
            font-size: 12px;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #e5ebea;
        }

        .bottom-note {
            margin-top: 22px;
            color: #7d888d;
            font-size: 13px;
        }

        .bottom-note a {
            margin-left: 4px;
        }

        /* FORM FEEDBACK */
        .status-message {
            display: none;
            margin: 0 0 16px;
            padding: 11px 13px;
            border-radius: 6px;
            font-size: 12px;
            line-height: 1.5;
            text-align: left;
        }

        .status-message.success {
            display: block;
            color: #176b4d;
            background: #e6f6ee;
            border: 1px solid #c5ead7;
        }

        .status-message.error {
            display: block;
            color: #9c3038;
            background: #fff0f0;
            border: 1px solid #f2cccc;
        }

        .auth-view[hidden] {
            display: none !important;
        }

/* RESET PASSWORD PAGE */

.password-requirements {
    text-align: left;
    margin: 12px 0 22px;
    padding: 14px 16px;
    background: #f7faf9;
    border-radius: 6px;
}

.password-requirements h4 {
    font-size: 11px;
    color: #687673;
    font-weight: 600;
    margin-bottom: 8px;
}

.password-requirements p {
    font-size: 11px;
    color: #899491;
    margin: 5px 0;
}

.password-requirements i {
    color: var(--teal);
    margin-right: 7px;
}

.reset-form .input-wrap {
    margin-bottom: 14px;
}

.reset-form .primary-btn {
    margin-top: 10px;
}

.reset-bottom {
    margin-top: 25px;
    font-size: 13px;
    color: #7d888d;
}

.reset-bottom a {
    color: var(--teal-dark);
    font-weight: 600;
    text-decoration: none;
}

.reset-bottom a:hover {
    text-decoration: underline;
}


        /* TABLET RESPONSIVENESS */
        @media (max-width: 760px) {
            body {
                padding: 24px 15px;
            }

            .auth-card {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .welcome-panel {
                min-height: 275px;
                padding: 75px 25px 35px;
            }

            .brand {
                top: 18px;
                left: 20px;
            }

            .welcome-content h2 {
                font-size: 27px;
            }

            .welcome-content p {
                margin-bottom: 20px;
            }

            .outline-btn {
                min-height: 43px;
            }

            .form-panel {
                padding: 38px 24px 42px;
            }

            .form-content {
                max-width: 460px;
            }
        }

        /* MOBILE RESPONSIVENESS */
        @media (max-width: 420px) {
            body {
                padding: 14px 10px;
            }

            .auth-card {
                border-radius: 12px;
            }

            .welcome-panel {
                min-height: 255px;
            }

            .form-panel {
                padding: 32px 18px 36px;
            }

            .form-options {
                font-size: 11px;
            }

            .form-content h1 {
                font-size: 27px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<main class="auth-shell">

    <!-- =================================
         REGISTRATION PAGE
    ================================== -->
    <section class="auth-view" id="registerView">

        <div class="auth-card">

            <aside class="welcome-panel">

                <a class="brand" href="#" aria-label="Diprella home">
                    <span class="brand-mark">
                        <i class="fa-solid fa-bezier-curve"></i>
                    </span>
                    <span>Diprella</span>
                </a>

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

                <a class="brand" href="#" aria-label="Diprella home">
                    <span class="brand-mark">
                        <i class="fa-solid fa-bezier-curve"></i>
                    </span>
                    <span>Diprella</span>
                </a>

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

                <a class="brand" href="#" aria-label="Diprella home">
                    <span class="brand-mark">
                        <i class="fa-solid fa-bezier-curve"></i>
                    </span>
                    <span>Diprella</span>
                </a>

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

        <!-- LEFT WELCOME PANEL -->
        <aside class="welcome-panel">

            <a class="brand" href="#" aria-label="Diprella home">
                <span class="brand-mark">
                    <i class="fa-solid fa-bezier-curve"></i>
                </span>
                <span>Diprella</span>
            </a>

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


        <!-- RIGHT FORM PANEL -->
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
                    <a href="#" data-show="login">Login</a>
                </p>

            </div>

        </div>

    </div>

</section>


</main>


<!-- =================================
     JAVASCRIPT
================================== -->
<script>

    // ALL AUTHENTICATION VIEWS
    
const views = {
    register: document.getElementById('registerView'),
    login: document.getElementById('loginView'),
    forgot: document.getElementById('forgotView'),
    reset: document.getElementById('resetView')
};



    // SWITCH BETWEEN PAGES
    function showView(viewName) {

        if (!views[viewName]) return;

        Object.entries(views).forEach(([name, element]) => {
            element.hidden = name !== viewName;
        });

        // Update URL hash
        history.replaceState(null, '', '#' + viewName);

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    // HANDLE ALL PAGE NAVIGATION LINKS
    document.querySelectorAll('[data-show]').forEach(control => {

        control.addEventListener('click', event => {

            event.preventDefault();

            showView(control.dataset.show);

        });

    });


    // SHOW AND HIDE PASSWORD
    document.querySelectorAll('[data-toggle]').forEach(button => {

        button.addEventListener('click', () => {

            const input = document.getElementById(button.dataset.toggle);
            const icon = button.querySelector('i');

            const reveal = input.type === 'password';

            input.type = reveal ? 'text' : 'password';

            icon.classList.toggle('fa-eye', !reveal);
            icon.classList.toggle('fa-eye-slash', reveal);

            button.setAttribute(
                'aria-label',
                reveal ? 'Hide password' : 'Show password'
            );

        });

    });


    // DISPLAY FORM MESSAGES
    function showStatus(id, message, type) {

        const box = document.getElementById(id);

        box.textContent = message;

        box.className = 'status-message ' + type;

    }


    // REGISTRATION FORM
    document.getElementById('registerForm').addEventListener('submit', event => {

        event.preventDefault();

        if (!event.currentTarget.reportValidity()) return;

        showStatus(
            'registerStatus',
            'Your details passed browser validation. Connect this form to your registration endpoint to create the account.',
            'success'
        );

    });


    // LOGIN FORM
    document.getElementById('loginForm').addEventListener('submit', event => {

        event.preventDefault();

        if (!event.currentTarget.reportValidity()) return;

        showStatus(
            'loginStatus',
            'Your details passed browser validation. Connect this form to your login endpoint to authenticate your account.',
            'success'
        );

    });


    // FORGOT PASSWORD FORM
    document.getElementById('forgotForm').addEventListener('submit', event => {

        event.preventDefault();

        if (!event.currentTarget.reportValidity()) return;

        showStatus(
            'forgotStatus',
            'The email address is valid. Connect this form to your password-reset endpoint to send a real reset link.',
            'success'
        );

    });


// RESET PASSWORD FORM
document.getElementById('resetForm').addEventListener('submit', event => {

    event.preventDefault();

    const form = event.currentTarget;

    const newPassword = document.getElementById('newPassword').value;

    const confirmPassword = document.getElementById('confirmPassword').value;


    // Check browser validation
    if (!form.reportValidity()) {
        return;
    }


    // Check password length
    if (newPassword.length < 8) {

        showStatus(
            'resetStatus',
            'Your password must contain at least 8 characters.',
            'error'
        );

        return;
    }


    // Check password complexity
    const strongPassword =
        /[A-Z]/.test(newPassword) &&
        /[a-z]/.test(newPassword) &&
        /[0-9]/.test(newPassword);

    if (!strongPassword) {

        showStatus(
            'resetStatus',
            'Please include uppercase and lowercase letters and at least one number.',
            'error'
        );

        return;
    }


    // Check whether passwords match
    if (newPassword !== confirmPassword) {

        showStatus(
            'resetStatus',
            'Your passwords do not match. Please try again.',
            'error'
        );

        return;
    }


    // Front-end demonstration only
    showStatus(
        'resetStatus',
        'Your new password passed validation. Connect this form to your password reset backend to update your account.',
        'success'
    );

});



    // OPEN PAGE BASED ON URL HASH
    // Default page is registration.

    const initialView = window.location.hash.replace('#', '');

    showView(
        views[initialView] ? initialView : 'register'
    );

</script>

</body>
</html>
