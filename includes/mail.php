<?php
/**
 * Enterprise Mailer System
 * Handles sending emails via SMTP socket connection for performance.
 */

class Mailer {
    
    /**
     * Send an email via SMTP.
     * 
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlBody
     * @return bool
     */
    public static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool {
        if (empty(MAIL_USER) || empty(MAIL_PASS)) {
            Logger::error("SMTP Credentials missing, cannot send email to $toEmail");
            return false;
        }

        try {
            $socket = @fsockopen('ssl://' . MAIL_HOST, MAIL_PORT, $errno, $errstr, 10);
            if (!$socket) {
                Logger::error("Failed to connect to SMTP server: $errstr ($errno)");
                return false;
            }

            self::readResponse($socket);
            self::sendCommand($socket, "EHLO " . $_SERVER['SERVER_NAME'] . "\r\n");
            self::sendCommand($socket, "AUTH LOGIN\r\n");
            self::sendCommand($socket, base64_encode(MAIL_USER) . "\r\n");
            self::sendCommand($socket, base64_encode(MAIL_PASS) . "\r\n");
            self::sendCommand($socket, "MAIL FROM:<" . MAIL_FROM . ">\r\n");
            self::sendCommand($socket, "RCPT TO:<$toEmail>\r\n");
            self::sendCommand($socket, "DATA\r\n");

            $msg  = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
            $msg .= "To: $toName <$toEmail>\r\n";
            $msg .= "Subject: $subject\r\n";
            $msg .= "MIME-Version: 1.0\r\n";
            $msg .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
            $msg .= $htmlBody . "\r\n.\r\n";

            self::sendCommand($socket, $msg);
            self::sendCommand($socket, "QUIT\r\n");
            fclose($socket);
            
            Logger::info("Email sent successfully to $toEmail", ['subject' => $subject]);
            return true;
            
        } catch (Throwable $e) {
            Logger::error("Exception while sending email: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    private static function sendCommand($socket, string $cmd) {
        fputs($socket, $cmd);
        return self::readResponse($socket);
    }

    private static function readResponse($socket) {
        $data = "";
        while ($str = fgets($socket, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == " ") { break; }
        }
        return $data;
    }
}
