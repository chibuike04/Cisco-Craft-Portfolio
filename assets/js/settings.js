
/* =========================================================
   CODECRAFT ADMIN - SETTINGS JAVASCRIPT
   Features:
   1. Profile form validation
   2. Password form validation
   3. Password confirmation
   4. Prevent duplicate submissions
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    /* =====================================================
       1. ELEMENT REFERENCES
    ===================================================== */

    const profileForm = document.getElementById("profileSettingsForm");
    const passwordForm = document.getElementById("passwordSettingsForm");

    const profileName = document.getElementById("profileName");
    const profileEmail = document.getElementById("profileEmail");

    const currentPassword = document.getElementById("currentPassword");
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");


    /* =====================================================
       2. PROFILE FORM VALIDATION
    ===================================================== */

    if (profileForm) {

        profileForm.addEventListener("submit", (event) => {

            const name = profileName?.value.trim() || "";
            const email = profileEmail?.value.trim() || "";

            if (!name) {
                event.preventDefault();
                alert("Please enter your full name.");
                profileName?.focus();
                return;
            }

            if (!email) {
                event.preventDefault();
                alert("Please enter your email address.");
                profileEmail?.focus();
                return;
            }

            if (!profileEmail.checkValidity()) {
                event.preventDefault();
                alert("Please enter a valid email address.");
                profileEmail.focus();
                return;
            }

            // Prevent accidental double submission.
            const submitButton = profileForm.querySelector(
                'button[type="submit"]'
            );

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Saving...';
            }

        });

    }


    /* =====================================================
       3. PASSWORD FORM VALIDATION
    ===================================================== */

    if (passwordForm) {

        passwordForm.addEventListener("submit", (event) => {

            const current = currentPassword?.value || "";
            const newPass = newPassword?.value || "";
            const confirmPass = confirmPassword?.value || "";

            if (!current) {
                event.preventDefault();
                alert("Please enter your current password.");
                currentPassword?.focus();
                return;
            }

            if (!newPass) {
                event.preventDefault();
                alert("Please enter your new password.");
                newPassword?.focus();
                return;
            }

            if (newPass.length < 8) {
                event.preventDefault();
                alert("Your new password must contain at least 8 characters.");
                newPassword?.focus();
                return;
            }

            if (newPass !== confirmPass) {
                event.preventDefault();
                alert("Your new password and confirmation do not match.");
                confirmPassword?.focus();
                return;
            }

            if (newPass === current) {
                event.preventDefault();
                alert("Your new password must be different from your current password.");
                newPassword?.focus();
                return;
            }

            // Prevent accidental double submission.
            const submitButton = passwordForm.querySelector(
                'button[type="submit"]'
            );

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Updating...';
            }

        });

    }


    /* =====================================================
       4. LIVE PASSWORD CONFIRMATION
    ===================================================== */

    function validatePasswordConfirmation() {

        if (!newPassword || !confirmPassword) {
            return;
        }

        if (
            confirmPassword.value !== "" &&
            newPassword.value !== confirmPassword.value
        ) {
            confirmPassword.setCustomValidity(
                "Your passwords do not match."
            );
        } else {
            confirmPassword.setCustomValidity("");
        }

    }

    if (newPassword && confirmPassword) {

        newPassword.addEventListener(
            "input",
            validatePasswordConfirmation
        );

        confirmPassword.addEventListener(
            "input",
            validatePasswordConfirmation
        );

    }


    /* =====================================================
       5. CLEAR PASSWORD FIELDS AFTER PAGE RESTORE
    ===================================================== */

    window.addEventListener("pageshow", () => {

        if (passwordForm) {
            passwordForm.reset();
        }

    });

});
