<?php
/**
 * MODÈLE EMAIL
 * Créer dans : model/Email.php
 */

class Email {
    private $id;
    private $recipient;
    private $subject;
    private $body;
    private $status;
    private $sent_at;
    private $error_message;
    
    public function __construct($id = null, $recipient, $subject, $body, $status = 'pending', $error = null) {
        $this->id = $id;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->body = $body;
        $this->status = $status;
        $this->error_message = $error;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getRecipient() { return $this->recipient; }
    public function getSubject() { return $this->subject; }
    public function getBody() { return $this->body; }
    public function getStatus() { return $this->status; }
    public function getErrorMessage() { return $this->error_message; }
    
    // Setters
    public function setStatus($status) { $this->status = $status; }
    public function setErrorMessage($error) { $this->error_message = $error; }
}
?>