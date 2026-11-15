<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

final class MailService
{
    /**
     * Create and configure a PHPMailer SMTP instance.
     */
    private function createMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);

        $mailer->isSMTP();
        $mailer->Host = (string) app_config('mail.host', 'smtp.gmail.com');
        $mailer->SMTPAuth = true;
        $mailer->Username = (string) app_config('mail.username', '');
        $mailer->Password = (string) app_config('mail.password', '');
        $mailer->Port = (int) app_config('mail.port', 587);

        $encryption = strtolower((string) app_config('mail.encryption', 'tls'));

        if ($encryption === 'tls') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $mailer->CharSet = PHPMailer::CHARSET_UTF8;
        $mailer->isHTML(true);

        $fromAddress = (string) app_config('mail.from_address', '');
        $fromName = (string) app_config('mail.from_name', 'Cisco Craft');

        if ($fromAddress === '' || $mailer->Username === '' || $mailer->Password === '') {
            throw new \RuntimeException('Mail configuration is incomplete.');
        }

        $mailer->setFrom($fromAddress, $fromName);

        return $mailer;
    }

    /**
     * Send an email to one recipient.
     */
    public function send(
        string $recipient,
        string $subject,
        string $htmlBody,
        string $plainTextBody = ''
    ): bool {
        try {
            $mailer = $this->createMailer();

            $mailer->addAddress($recipient);
            $mailer->Subject = $subject;
            $mailer->Body = $htmlBody;
            $mailer->AltBody = $plainTextBody !== ''
                ? $plainTextBody
                : trim(strip_tags($htmlBody));

            return $mailer->send();
        } catch (MailException | \RuntimeException $exception) {
            error_log('Mail sending failed: ' . $exception->getMessage());

            return false;
        } catch (\Throwable $exception) {
            error_log('Unexpected mail error: ' . $exception->getMessage());

            return false;
        }
    }
}
