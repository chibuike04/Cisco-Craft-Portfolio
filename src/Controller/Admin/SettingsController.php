<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Helpers\Auth;
use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Models\User;
use App\View\View;

final class SettingsController extends ControllerSupport
{
    /**
     * Display the admin settings page.
     */
    public function index(Request $request): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            $this->redirectTo(url('/admin/login'));
            return;
        }

        $admin = (new User())->find($userId);

        if ($admin === null) {
            Auth::logout();
            $this->redirectTo(url('/admin/login'));
            return;
        }

        View::render('admin/settings', [
            'admin' => $admin,
            'pageTitle' => 'Admin Settings',
            'csrfToken' => Csrf::token(),
        ]);
    }

    /**
     * Process profile and password updates.
     */
    public function update(Request $request): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            $this->redirectTo(url('/admin/login'));
            return;
        }

        $userModel = new User();
        $admin = $userModel->find($userId);

        if ($admin === null) {
            Auth::logout();
            $this->redirectTo(url('/admin/login'));
            return;
        }

        $input = $request->all();
        $action = trim((string) ($input['action'] ?? ''));

        /*
        |--------------------------------------------------------------------------
        | Update Profile Information
        |--------------------------------------------------------------------------
        */
        if ($action === 'update_profile') {
            $name = trim((string) ($input['name'] ?? ''));
            $email = strtolower(trim((string) ($input['email'] ?? '')));

            if ($name === '' || $email === '') {
                Flash::error('Name and email address are required.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Flash::error('Please enter a valid email address.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            // Check whether another account already uses this email.
            $existingUser = $userModel->findByEmail($email);

            if (
                $existingUser !== null
                && (int) $existingUser['id'] !== $userId
            ) {
                Flash::error('That email address is already in use.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            $updated = $userModel->update($userId, [
                'name' => $name,
                'email' => $email,
            ]);

            if (!$updated) {
                Flash::error('Unable to update your profile. Please try again.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            // Refresh the authenticated session with the updated details.
            $updatedAdmin = $userModel->find($userId);

            if ($updatedAdmin !== null) {
                Auth::login($updatedAdmin);
            }

            Flash::success('Your profile information has been updated.');
            $this->redirectTo(url('/admin/settings'));
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */
        if ($action === 'update_password') {
            $currentPassword = (string) ($input['current_password'] ?? '');
            $newPassword = (string) ($input['new_password'] ?? '');
            $confirmPassword = (string) ($input['confirm_password'] ?? '');

            if (
                $currentPassword === ''
                || $newPassword === ''
                || $confirmPassword === ''
            ) {
                Flash::error('Please complete all password fields.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            if (!password_verify(
                $currentPassword,
                (string) $admin['password_hash']
            )) {
                Flash::error('Your current password is incorrect.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            if (strlen($newPassword) < 8) {
                Flash::error('Your new password must contain at least 8 characters.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            if ($newPassword !== $confirmPassword) {
                Flash::error('Your new password and confirmation do not match.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            if (password_verify(
                $newPassword,
                (string) $admin['password_hash']
            )) {
                Flash::error('Your new password must be different from your current password.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            $passwordHash = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

            if (!$userModel->updatePasswordHash($userId, $passwordHash)) {
                Flash::error('Unable to update your password. Please try again.');
                $this->redirectTo(url('/admin/settings'));
                return;
            }

            Flash::success('Your password has been updated successfully.');
            $this->redirectTo(url('/admin/settings'));
            return;
        }

        Flash::error('Invalid settings action.');
        $this->redirectTo(url('/admin/settings'));
    }
}
