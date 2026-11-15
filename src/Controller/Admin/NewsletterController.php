<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Helpers\Csrf;
use App\Models\NewsletterSubscriber;
use App\View\View;
use Throwable;

final class NewsletterController extends ControllerSupport
{
    /**
     * Handle public newsletter subscriptions.
     */
    public function subscribe(Request $request): void
    {
        $email = strtolower(trim((string) $request->input('email', '')));
        $name = trim((string) $request->input('name', ''));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ], 422);

            return;
        }

        try {
            $subscriberModel = new NewsletterSubscriber();

            $existingSubscriber = $subscriberModel->firstWhere(
                'email',
                $email
            );

            if ($existingSubscriber !== null) {
                $subscriberId = (int) ($existingSubscriber['id'] ?? 0);

                $existingStatus = strtolower(
                    (string) ($existingSubscriber['status'] ?? '')
                );

                // Reactivate an unsubscribed email address.
                if ($existingStatus === 'unsubscribed' && $subscriberId > 0) {
                    $subscribedAt = date('Y-m-d H:i:s');

                    $updated = $subscriberModel->update($subscriberId, [
                        'status' => 'active',
                        'subscribed_at' => $subscribedAt,
                        'unsubscribed_at' => null
                    ]);

                    if (!$updated) {
                        $this->json([
                            'success' => false,
                            'message' => 'Unable to reactivate your subscription. Please try again.'
                        ], 500);

                        return;
                    }

                    $this->json([
                        'success' => true,
                        'message' => 'Welcome back! Your newsletter subscription has been reactivated.'
                    ]);

                    return;
                }

                // Prevent duplicate subscriptions.
                $this->json([
                    'success' => true,
                    'message' => 'This email address is already subscribed to our newsletter.'
                ]);

                return;
            }

            // Create a new subscriber.
            $subscriberModel->create([
                'email' => $email,
                'name' => $name !== '' ? $name : null,
                'status' => 'active',
                'subscribed_at' => date('Y-m-d H:i:s'),
                'confirmed_at' => null,
                'unsubscribed_at' => null
            ]);

            $this->json([
                'success' => true,
                'message' => 'Thank you for subscribing! You have been added to our newsletter.'
            ]);

        } catch (Throwable $exception) {
            error_log(
                'Newsletter subscription error: ' .
                $exception->getMessage() .
                ' in ' . $exception->getFile() .
                ' on line ' . $exception->getLine()
            );

            $this->json([
                'success' => false,
                'message' => 'Something went wrong while processing your subscription. Please try again later.'
            ], 500);
        }
    }

    /**
     * Display newsletter subscribers and statistics.
     */
    public function index(Request $request): void
    {
        // TEMPORARY TEST:
        // Confirms whether the updated controller is running.
       

        try {
            $subscriberModel = new NewsletterSubscriber();

            // Retrieve the newest subscribers first.
            $subscribers = $subscriberModel->all(
                500,
                0,
                'id',
                'DESC'
            );

            if (!is_array($subscribers)) {
                $subscribers = [];
            }

            // Calculate subscriber statistics.
            $totalSubscribers = count($subscribers);
            $activeSubscribers = 0;
            $unsubscribedSubscribers = 0;

            foreach ($subscribers as $subscriber) {
                $status = strtolower(
                    (string) ($subscriber['status'] ?? '')
                );

                if ($status === 'active') {
                    $activeSubscribers++;
                } elseif ($status === 'unsubscribed') {
                    $unsubscribedSubscribers++;
                }
            }

            View::render('admin/newsletter', [
                'subscribers' => $subscribers,
                'totalSubscribers' => $totalSubscribers,
                'activeSubscribers' => $activeSubscribers,
                'unsubscribedSubscribers' => $unsubscribedSubscribers,
                'csrfToken' => Csrf::token()
            ]);

        } catch (Throwable $exception) {
            error_log(
                'Newsletter page rendering error: ' .
                $exception->getMessage() .
                ' in ' . $exception->getFile() .
                ' on line ' . $exception->getLine()
            );

            http_response_code(500);

            echo '<pre style="padding:20px; color:#b91c1c; background:#fff; font-size:14px;">';

            echo '<strong>NEWSLETTER ERROR:</strong>' . "\n\n";

            echo htmlspecialchars(
                $exception->getMessage(),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            );

            echo "\n\nFile: " . htmlspecialchars(
                $exception->getFile(),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            );

            echo "\nLine: " . $exception->getLine();

            echo '</pre>';
        }
    }

    /**
     * Update a subscriber's status.
     */
    public function updateStatus(Request $request, int $id): void
    {
        try {
            $subscriberModel = new NewsletterSubscriber();

            $subscriber = $subscriberModel->find($id);

            if (!$subscriber) {
                $this->json([
                    'success' => false,
                    'message' => 'Subscriber not found.'
                ], 404);

                return;
            }

            $status = strtolower(
                trim((string) $request->input('status', ''))
            );

            if (!in_array($status, ['active', 'unsubscribed'], true)) {
                $this->json([
                    'success' => false,
                    'message' => 'Invalid subscriber status.'
                ], 422);

                return;
            }

            $updatedAt = date('Y-m-d H:i:s');

            $updateData = [
                'status' => $status
            ];

            if ($status === 'active') {
                $updateData['unsubscribed_at'] = null;

                if (empty($subscriber['subscribed_at'])) {
                    $updateData['subscribed_at'] = $updatedAt;
                }
            } else {
                $updateData['unsubscribed_at'] = $updatedAt;
            }

            $updated = $subscriberModel->update($id, $updateData);

            if (!$updated) {
                $this->json([
                    'success' => false,
                    'message' => 'Unable to update subscriber status.'
                ], 500);

                return;
            }

            $this->json([
                'success' => true,
                'message' => 'Subscriber status updated successfully.',
                'status' => $status,
                'unsubscribed_at' => $status === 'unsubscribed'
                    ? $updatedAt
                    : null
            ]);

        } catch (Throwable $exception) {
            error_log(
                'Newsletter status update error: ' .
                $exception->getMessage()
            );

            $this->json([
                'success' => false,
                'message' => 'An error occurred while updating the subscriber.'
            ], 500);
        }
    }

    /**
     * Delete a newsletter subscriber.
     */
    public function destroy(Request $request, int $id): void
    {
        try {
            $subscriberModel = new NewsletterSubscriber();

            $subscriber = $subscriberModel->find($id);

            if (!$subscriber) {
                $this->json([
                    'success' => false,
                    'message' => 'Subscriber not found.'
                ], 404);

                return;
            }

            $deleted = $subscriberModel->delete($id);

            if (!$deleted) {
                $this->json([
                    'success' => false,
                    'message' => 'Unable to delete subscriber.'
                ], 500);

                return;
            }

            $this->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully.'
            ]);

        } catch (Throwable $exception) {
            error_log(
                'Newsletter deletion error: ' .
                $exception->getMessage()
            );

            $this->json([
                'success' => false,
                'message' => 'An error occurred while deleting the subscriber.'
            ], 500);
        }
    }
}
