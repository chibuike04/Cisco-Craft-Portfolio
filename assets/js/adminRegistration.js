
/* =========================================
   AUTHENTICATION CONFIGURATION
========================================= */

const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.content || '';

const appUrl = (
    document.querySelector('meta[name="app-url"]')?.content || ''
).replace(/\/+$/, '');


/* =========================================
   ALL AUTHENTICATION VIEWS
========================================= */

const views = {
    register: document.getElementById('registerView'),
    login: document.getElementById('loginView'),
    forgot: document.getElementById('forgotView'),
    reset: document.getElementById('resetView')
};


/* =========================================
   SWITCH BETWEEN PAGES
========================================= */

function showView(viewName) {

    if (!views[viewName]) return;

    Object.entries(views).forEach(([name, element]) => {

        if (element) {
            element.hidden = name !== viewName;
        }

    });

    history.replaceState(null, '', '#' + viewName);

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

}


/* =========================================
   PAGE NAVIGATION
========================================= */

document.querySelectorAll('[data-show]').forEach(control => {

    control.addEventListener('click', event => {

        event.preventDefault();

        showView(control.dataset.show);

    });

});


/* =========================================
   PASSWORD VISIBILITY
========================================= */

document.querySelectorAll('[data-toggle]').forEach(button => {

    button.addEventListener('click', () => {

        const input = document.getElementById(button.dataset.toggle);
        const icon = button.querySelector('i');

        if (!input || !icon) return;

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


/* =========================================
   DISPLAY FORM MESSAGES
========================================= */

function showStatus(id, message, type = 'error') {

    const box = document.getElementById(id);

    if (!box) return;

    box.textContent = message;
    box.className = 'status-message ' + type;

}


/* =========================================
   RESOLVE REDIRECT URL
========================================= */

function resolveRedirect(redirect) {

    if (!redirect) {
        return appUrl + '/admin/dashboard';
    }

    // Preserve absolute URLs returned by the backend.
    if (/^https?:\/\//i.test(redirect)) {
        return redirect;
    }

    // Support application-relative routes on both environments.
    return appUrl + '/' + redirect.replace(/^\/+/, '');

}


/* =========================================
   SEND AUTHENTICATION REQUEST
========================================= */

async function sendAuthRequest(endpoint, formData) {

    if (!appUrl) {
        throw new Error(
            'Application URL is missing. Please check the authentication page configuration.'
        );
    }

    const response = await fetch(
        `${appUrl}${endpoint}`,
        {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },

            credentials: 'same-origin',

            body: JSON.stringify(formData)
        }
    );

    let result;

    try {

        result = await response.json();

    } catch (error) {

        throw new Error(
            'The server returned an invalid response. Please try again.'
        );

    }

    if (!response.ok || !result.success) {

        throw new Error(
            result.message || 'Something went wrong. Please try again.'
        );

    }

    return result;

}


/* =========================================
   HANDLE FORM SUBMISSION
========================================= */

function setFormLoading(form, loading, originalText) {

    const button = form.querySelector('button[type="submit"]');

    if (!button) return;

    button.disabled = loading;

    button.textContent = loading
        ? 'PLEASE WAIT...'
        : originalText;

}


/* =========================================
   REGISTRATION FORM
========================================= */

const registerForm = document.getElementById('registerForm');

registerForm?.addEventListener('submit', async event => {

    event.preventDefault();

    const form = event.currentTarget;

    if (!form.reportValidity()) return;

    const formData = new FormData(form);

    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password')
    };

    const originalText = 'SIGN UP';

    setFormLoading(form, true, originalText);

    showStatus(
        'registerStatus',
        'Creating your account...',
        'success'
    );

    try {

        const result = await sendAuthRequest(
            '/admin/register',
            data
        );

        showStatus(
            'registerStatus',
            result.message || 'Account created successfully.',
            'success'
        );

        form.reset();

        setTimeout(() => {

            window.location.href = resolveRedirect(result.redirect);

        }, 1800);

    } catch (error) {

        showStatus(
            'registerStatus',
            error.message,
            'error'
        );

    } finally {

        setFormLoading(form, false, originalText);

    }

});


/* =========================================
   LOGIN FORM
========================================= */

const loginForm = document.getElementById('loginForm');

loginForm?.addEventListener('submit', async event => {

    event.preventDefault();

    const form = event.currentTarget;

    if (!form.reportValidity()) return;

    const formData = new FormData(form);

    const data = {
        email: formData.get('email'),
        password: formData.get('password')
    };

    const originalText = 'LOGIN';

    setFormLoading(form, true, originalText);

    showStatus(
        'loginStatus',
        'Verifying your credentials...',
        'success'
    );

    try {

        const result = await sendAuthRequest(
            '/admin/login',
            data
        );

        showStatus(
            'loginStatus',
            result.message || 'Login successful.',
            'success'
        );

        window.location.href = resolveRedirect(result.redirect);

    } catch (error) {

        showStatus(
            'loginStatus',
            error.message,
            'error'
        );

    } finally {

        setFormLoading(form, false, originalText);

    }

});


/* =========================================
   FORGOT PASSWORD
   Backend will be connected in phase 2.
========================================= */

const forgotForm = document.getElementById('forgotForm');

forgotForm?.addEventListener('submit', event => {

    event.preventDefault();

    const form = event.currentTarget;

    if (!form.reportValidity()) return;

    showStatus(
        'forgotStatus',
        'Password recovery will be connected in the next authentication phase.',
        'error'
    );

});


/* =========================================
   RESET PASSWORD
   Backend will be connected in phase 2.
========================================= */

const resetForm = document.getElementById('resetForm');

resetForm?.addEventListener('submit', event => {

    event.preventDefault();

    const form = event.currentTarget;

    if (!form.reportValidity()) return;

    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword.length < 8) {

        showStatus(
            'resetStatus',
            'Your password must contain at least 8 characters.',
            'error'
        );

        return;

    }

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

    if (newPassword !== confirmPassword) {

        showStatus(
            'resetStatus',
            'Your passwords do not match. Please try again.',
            'error'
        );

        return;

    }

    showStatus(
        'resetStatus',
        'Password reset will be connected in the next authentication phase.',
        'error'
    );

});


/* =========================================
   OPEN THE CORRECT PAGE
========================================= */

const initialView =
    document.body.dataset.initialView ||
    window.location.hash.replace('#', '');

showView(
    views[initialView] ? initialView : 'login'
);
