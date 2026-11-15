<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Helpers\Flash;
use App\Models\Message;
use App\Services\MailService;
use App\View\View;
use Throwable;

final class MessageController extends ControllerSupport
{
    /**
     * Display all contact messages.
     */
    public function index(Request $request): void
    {
        try {
            $messageModel = new Message();

            $messages = $messageModel->all(
                500,
                0,
                'created_at',
                'DESC'
            );

            $totalMessages = count($messages);
            $unreadMessages = 0;

            foreach ($messages as &$message) {
                $isRead = !empty($message['read_at']);

                $message['is_read'] = $isRead;

                if (!$isRead) {
                    $unreadMessages++;
                }
            }

            unset($message);

            View::render('admin/messages', [
                'messages' => $messages,
                'totalMessages' => $totalMessages,
                'unreadMessages' => $unreadMessages,
                'pageTitle' => 'Contact Messages',
                'messagesConfigured' => true,
            ]);
        } catch (Throwable $exception) {
            error_log(
                'Message loading failed: ' . $exception->getMessage()
            );

            Flash::error('Unable to load contact messages.');

            $this->redirectTo(url('/admin'));
        }
    }

    /**
     * Retrieve a single contact message.
     */
    public function show(Request $request, int $id): void
    {
        try {
            $message = (new Message())->find($id);

            if (!$message) {
                $this->respondWithError(
                    $request,
                    'Message not found.',
                    404
                );

                Flash::error('Message not found.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $this->respondWithSuccess(
                $request,
                'Message retrieved successfully.',
                ['message' => $message]
            );

            $this->redirectTo(url('/admin/messages'));
        } catch (Throwable $exception) {
            error_log(
                'Message retrieval failed: ' . $exception->getMessage()
            );

            $this->respondWithError(
                $request,
                'Unable to retrieve the message.',
                500
            );

            Flash::error('Unable to retrieve the message.');
            $this->redirectTo(url('/admin/messages'));
        }
    }

    /**
     * Toggle a message between read and unread.
     */
    public function toggleStatus(Request $request, int $id): void
    {
        try {
            $messageModel = new Message();
            $message = $messageModel->find($id);

            if (!$message) {
                $this->respondWithError(
                    $request,
                    'Message not found.',
                    404
                );

                Flash::error('Message not found.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $isCurrentlyRead = !empty($message['read_at']);

            $newStatus = $isCurrentlyRead ? 'unread' : 'read';

            $readAt = $isCurrentlyRead
                ? null
                : date('Y-m-d H:i:s');

            $updated = $messageModel->update($id, [
                'status' => $newStatus,
                'read_at' => $readAt,
            ]);

            if (!$updated) {
                $this->respondWithError(
                    $request,
                    'Unable to update the message status.',
                    500
                );

                Flash::error('Unable to update the message status.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $this->respondWithSuccess(
                $request,
                'Message marked as ' . $newStatus . '.',
                [
                    'id' => $id,
                    'status' => $newStatus,
                    'is_read' => !$isCurrentlyRead,
                    'read_at' => $readAt,
                ]
            );

            Flash::success(
                'Message marked as ' . $newStatus . '.'
            );

            $this->redirectTo(url('/admin/messages'));
        } catch (Throwable $exception) {
            error_log(
                'Message status update failed: ' . $exception->getMessage()
            );

            $this->respondWithError(
                $request,
                'Unable to update the message status.',
                500
            );

            Flash::error('Unable to update the message status.');
            $this->redirectTo(url('/admin/messages'));
        }
    }

    /**
     * Delete a contact message.
     */
    public function destroy(Request $request, int $id): void
    {
        try {
            $messageModel = new Message();
            $message = $messageModel->find($id);

            if (!$message) {
                $this->respondWithError(
                    $request,
                    'Message not found.',
                    404
                );

                Flash::error('Message not found.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $deleted = $messageModel->delete($id);

            if (!$deleted) {
                $this->respondWithError(
                    $request,
                    'Unable to delete the message.',
                    500
                );

                Flash::error('Unable to delete the message.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $this->respondWithSuccess(
                $request,
                'Message deleted successfully.',
                ['id' => $id]
            );

            Flash::success('Message deleted successfully.');

            $this->redirectTo(url('/admin/messages'));
        } catch (Throwable $exception) {
            error_log(
                'Message deletion failed: ' . $exception->getMessage()
            );

            $this->respondWithError(
                $request,
                'Unable to delete the message.',
                500
            );

            Flash::error('Unable to delete the message.');
            $this->redirectTo(url('/admin/messages'));
        }
    }

    /**
     * Send an email reply to the person who submitted a contact message.
     */
    public function sendReply(Request $request, int $id): void
    {
        try {
            $messageModel = new Message();

            // Retrieve the original contact message.
            $originalMessage = $messageModel->find($id);

            if (!$originalMessage) {
                $this->respondWithError(
                    $request,
                    'The original message could not be found.',
                    404
                );

                Flash::error('The original message could not be found.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            // Always use the recipient email stored in the database.
            $recipient = trim(
                (string) ($originalMessage['email'] ?? '')
            );

            $subject = trim(
                (string) $request->input('subject', '')
            );

            $reply = trim(
                (string) $request->input('message', '')
            );

            // Validate recipient.
            if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $this->respondWithError(
                    $request,
                    'The recipient email address is invalid.',
                    422
                );

                Flash::error('The recipient email address is invalid.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            // Validate subject and reply.
            if ($subject === '' || $reply === '') {
                $this->respondWithError(
                    $request,
                    'Please provide both an email subject and reply message.',
                    422
                );

                Flash::error(
                    'Please provide both an email subject and reply message.'
                );

                $this->redirectTo(url('/admin/messages'));
                return;
            }

            // Prevent excessively large email content.
            if (mb_strlen($subject) > 255 || mb_strlen($reply) > 20000) {
                $this->respondWithError(
                    $request,
                    'The email subject or message is too long.',
                    422
                );

                Flash::error('The email subject or message is too long.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            // Escape reply content before placing it in HTML.
            $escapedReply = nl2br(
                htmlspecialchars(
                    $reply,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                )
            );

            // HTML email body.
            $htmlBody = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                        <p>' . $escapedReply . '</p>

                        <br>

                        <p>
                            Best regards,<br>
                            <strong>Cisco Craft</strong>
                        </p>
                    </div>
                </body>
                </html>
            ';

            // Plain-text alternative for email clients.
            $plainTextBody = $reply
                . "\n\nBest regards,\nCisco Craft";

            // Send using the configured Gmail SMTP service.
            $mailService = new MailService();

            $sent = $mailService->send(
                $recipient,
                $subject,
                $htmlBody,
                $plainTextBody
            );

            if (!$sent) {
                $this->respondWithError(
                    $request,
                    'The email could not be sent. Please check your mail configuration and try again.',
                    500
                );

                Flash::error('The email could not be sent.');
                $this->redirectTo(url('/admin/messages'));
                return;
            }

            $this->respondWithSuccess(
                $request,
                'Your email reply was sent successfully.',
                [
                    'id' => $id,
                    'recipient' => $recipient,
                    'subject' => $subject,
                ]
            );

            Flash::success('Your email reply was sent successfully.');
            $this->redirectTo(url('/admin/messages'));
        } catch (Throwable $exception) {
            error_log(
                'Message reply failed: ' . $exception->getMessage()
            );

            $this->respondWithError(
                $request,
                'An error occurred while sending your email reply.',
                500
            );

            Flash::error(
                'An error occurred while sending your email reply.'
            );

            $this->redirectTo(url('/admin/messages'));
        }
    }

    /**
     * Determine whether the request expects JSON.
     */
    private function expectsJson(Request $request): bool
    {
        $accept = strtolower(
            (string) ($request->header('Accept') ?? '')
        );

        $requestedWith = strtolower(
            (string) ($request->header('X-Requested-With') ?? '')
        );

        return str_contains($accept, 'application/json')
            || $requestedWith === 'xmlhttprequest';
    }

    /**
     * Send a successful JSON response.
     */
    private function respondWithSuccess(
        Request $request,
        string $message,
        array $data = [],
        int $statusCode = 200
    ): void {
        if (!$this->expectsJson($request)) {
            return;
        }

        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        exit;
    }

    /**
     * Send an error JSON response.
     */
    private function respondWithError(
        Request $request,
        string $message,
        int $statusCode = 400
    ): void {
        if (!$this->expectsJson($request)) {
            return;
        }

        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $message,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        exit;
    }
}
