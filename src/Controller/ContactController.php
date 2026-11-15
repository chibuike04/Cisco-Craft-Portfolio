<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Helpers\Validation;
use App\Models\Message;
use App\View\View;

final class ContactController extends ControllerSupport
{
    /**
     * Display the contact page.
     */
    public function index(Request $request): void
    {
        View::render('public/contact', [
            'pageTitle' => 'Contact',
            'csrfToken' => Csrf::token(),
        ]);
    }

    /**
     * Process and store contact form submissions.
     */
    public function store(Request $request): void
    {
        // Verify CSRF token.
        if (!Csrf::verify((string) $request->input('_token', ''))) {
            Flash::error(
                'Your session has expired. Please refresh the page and try again.'
            );

            $this->redirectTo(url('/contact'));
            return;
        }

        // Retrieve submitted form data.
        $data = [
            'name' => trim((string) $request->input('name', '')),
            'email' => trim((string) $request->input('email', '')),
            'message' => trim((string) $request->input('message', '')),
        ];

        // Validate submitted data.
        $errors = Validation::validate($data, [
            'name' => 'required|string|min:2|max:150',
            'email' => 'required|email|max:190',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Display friendly validation messages instead of raw arrays.
        if (!empty($errors)) {
            $messageLength = function_exists('mb_strlen')
                ? mb_strlen($data['message'], 'UTF-8')
                : strlen($data['message']);

            if ($messageLength < 10) {
                Flash::error(
                    'Your message should not be less than 10 characters.'
                );
            } elseif (empty($data['name']) || strlen($data['name']) < 2) {
                Flash::error(
                    'Please enter your name (at least 2 characters).'
                );
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Flash::error(
                    'Please enter a valid email address.'
                );
            } else {
                Flash::error(
                    'Please check your contact form details and try again.'
                );
            }

            $this->redirectTo(url('/contact'));
            return;
        }

        // Collect basic submission information.
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        // Save the message to the database.
        $messageModel = new Message();

        $messageModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'status' => 'unread',
            'ip_address' => $ipAddress
                ? substr($ipAddress, 0, 45)
                : null,
            'user_agent' => $userAgent
                ? substr($userAgent, 0, 500)
                : null,
        ]);

        Flash::success(
            'Your message has been sent successfully! I will get back to you soon.'
        );

        $this->redirectTo(url('/contact'));
    }
}
