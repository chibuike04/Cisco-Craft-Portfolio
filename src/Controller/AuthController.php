<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Helpers\Auth;
use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Helpers\Session;
use App\Models\User;
use App\View\View;
use PDOException;

final class AuthController extends ControllerSupport
{
    private const INVITE_SESSION_KEY = '_admin_registration_invite';
    private const INVITE_DURATION = 900; // 15 minutes

    /**
     * Display the registration page.
     * Registration requires a valid private invitation.
     */
    public function showRegister(Request $request): void
    {
        if (Auth::check()) {
            $this->redirectTo(url('/admin/dashboard'));
        }

        // Prevent the invitation URL from being sent as a referrer.
        header('Referrer-Policy: no-referrer');

        Session::start();

        $invite = $request->input('invite');
        $expectedKey = (string) ($_ENV['ADMIN_REGISTRATION_KEY'] ?? '');

        // Validate an invitation supplied in the URL.
        if (is_string($invite) && $invite !== '') {
            if (
                $expectedKey === ''
                || !hash_equals($expectedKey, $invite)
            ) {
                Response::text(
                    'This registration invitation is invalid.',
                    403
                );
            }

            // Keep permission in the session, not in the URL.
            $_SESSION[self::INVITE_SESSION_KEY] =
                time() + self::INVITE_DURATION;

            // Remove the invitation key from the address bar.
            $this->redirectTo(url('/admin/register'));
        }

        // Check the short-lived session permission.
        $expiresAt = $_SESSION[self::INVITE_SESSION_KEY] ?? 0;

        if (
            !is_int($expiresAt)
            || $expiresAt < time()
        ) {
            unset($_SESSION[self::INVITE_SESSION_KEY]);

            Response::text(
                'A valid registration invitation is required.',
                403
            );
        }

        $this->renderAuthPage('register');
    }

    /**
     * Process a new administrator registration.
     */
    public function register(Request $request): void
    {
        Session::start();

        $expiresAt = $_SESSION[self::INVITE_SESSION_KEY] ?? 0;

        if (
            !is_int($expiresAt)
            || $expiresAt < time()
        ) {
            unset($_SESSION[self::INVITE_SESSION_KEY]);

            Response::json([
                'success' => false,
                'message' => 'Your registration invitation has expired. Request a new invitation.',
            ], 403);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (
            !is_string($name)
            || !is_string($email)
            || !is_string($password)
        ) {
            Response::json([
                'success' => false,
                'message' => 'Please provide valid registration details.',
            ], 422);
        }

        $name = trim($name);
        $email = strtolower(trim($email));

        if (
            $name === ''
            || strlen($name) > 150
        ) {
            Response::json([
                'success' => false,
                'message' => 'Please enter a name containing no more than 150 characters.',
            ], 422);
        }

        if (
            strlen($email) > 190
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            Response::json([
                'success' => false,
                'message' => 'Please enter a valid email address.',
            ], 422);
        }

        if (
            strlen($password) < 8
            || strlen($password) > 72
        ) {
            Response::json([
                'success' => false,
                'message' => 'Your password must contain between 8 and 72 bytes.',
            ], 422);
        }

        $userModel = new User();

        if ($userModel->emailExists($email)) {
            Response::json([
                'success' => false,
                'message' => 'An account with this email address already exists.',
            ], 409);
        }

        try {
            $userModel->create([
                'role_id' => 1,
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash(
                    $password,
                    PASSWORD_DEFAULT
                ),
                'status' => 'pending',
            ]);
        } catch (PDOException $exception) {
            // Also handles a duplicate email caught by the database index.
            if ($exception->getCode() === '23000') {
                Response::json([
                    'success' => false,
                    'message' => 'An account with this email address already exists.',
                ], 409);
            }

            throw $exception;
        }

        // A registration invitation can only create one account.
        unset($_SESSION[self::INVITE_SESSION_KEY]);

        Response::json([
            'success' => true,
            'message' => 'Your account has been created and is awaiting administrator approval. You can log in after your account is approved.',
            'redirect' => url('/admin/login'),
        ], 201);
    }

    /**
     * Display the login page.
     */
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            $this->redirectTo(url('/admin/dashboard'));
        }

        $this->renderAuthPage('login');
    }

    /**
     * Authenticate an administrator.
     */
    public function login(Request $request): void
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (
            !is_string($email)
            || !is_string($password)
        ) {
            Response::json([
                'success' => false,
                'message' => 'Please enter your email and password.',
            ], 422);
        }

        $email = strtolower(trim($email));

        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL)
            || strlen($email) > 190
            || $password === ''
        ) {
            Response::json([
                'success' => false,
                'message' => 'Please check your email and password.',
            ], 422);
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Use the same message for nonexistent users and wrong passwords.
        if (
            $user === null
            || !is_string($user['password_hash'] ?? null)
            || !password_verify($password, $user['password_hash'])
        ) {
            Response::json([
                'success' => false,
                'message' => 'Incorrect email address or password.',
            ], 401);
        }

        // Only approved accounts can authenticate.
        if (($user['status'] ?? '') === 'pending') {
            Response::json([
                'success' => false,
                'message' => 'Your account is awaiting administrator approval.',
            ], 403);
        }

        if (($user['status'] ?? '') !== 'approved') {
            Response::json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact the administrator.',
            ], 403);
        }

        Auth::login($user);

        $userModel->updateLastLogin((int) $user['id']);

        Response::json([
            'success' => true,
            'message' => 'Login successful. Redirecting to your dashboard...',
            'redirect' => url('/admin/dashboard'),
        ]);
    }

    /**
     * Log out the authenticated administrator.
     */
    public function logout(Request $request): void
    {
        if (!Auth::check()) {
            $this->redirectTo(url('/admin/login'));
        }

        Auth::logout();

        $this->redirectTo(url('/admin/login'));
    }

    /**
     * Render the shared four-screen authentication template.
     */
    private function renderAuthPage(string $activeView): void
    {
        View::render(
            'admin/admin-registration/adminRegistration',
            [
                'pageTitle' => 'Admin Authentication',
                'csrfToken' => Csrf::token(),
                'activeView' => $activeView,
                'appUrl' => rtrim(
                    (string) ($_ENV['APP_URL'] ?? ''),
                    '/'
                ),
            ]
        );
    }
}
