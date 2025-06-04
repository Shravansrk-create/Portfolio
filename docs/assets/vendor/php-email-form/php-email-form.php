<?php
/**
 * Simple PHP Email Form class
 * Supports adding messages and sending email via mail() function or SMTP.
 */
class PHP_Email_Form {
    public $ajax = false;
    public $to = '';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $smtp = null; // Array with SMTP settings (optional)
    
    private $messages = [];

    /**
     * Add a message line with optional label and priority
     */
    public function add_message($message, $label = '', $priority = 0) {
        $this->messages[] = ['label' => $label, 'message' => $message, 'priority' => $priority];
    }

    /**
     * Send the email
     */
    public function send() {
        // Build the message body from added messages
        $body = '';
        foreach ($this->messages as $msg) {
            if ($msg['label']) {
                $body .= $msg['label'] . ': ';
            }
            $body .= $msg['message'] . "\n";
        }

        // Build headers
        $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if ($this->smtp) {
            // If SMTP is set, use SMTP sending (needs PHPMailer or similar)
            // For simplicity, here we return false (not implemented)
            return $this->send_smtp($body, $headers);
        } else {
            // Use mail()
            $success = mail($this->to, $this->subject, $body, $headers);
            if ($success) {
                return json_encode(['status' => 'OK', 'message' => 'Email sent successfully']);
            } else {
                return json_encode(['status' => 'ERROR', 'message' => 'Failed to send email']);
            }
        }
    }

    /**
     * Stub for SMTP sending - you can extend this using PHPMailer or similar
     */
    private function send_smtp($body, $headers) {
        // You can integrate PHPMailer or SwiftMailer here to send using SMTP
        // For now, we just return error
        return json_encode(['status' => 'ERROR', 'message' => 'SMTP sending not implemented']);
    }
}
