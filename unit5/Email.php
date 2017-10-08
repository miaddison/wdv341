<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 9/27/17
	Course: WDV341 Intro to PHP
-->
<?php

	class Email{
		// Variables
		private $message;	// content for body of email
		private $recipient;	// send to email address
		private $sender;	// from email address
		private $subject;	// content for subject line
		
		// Constructor method goes here
		public function __construct($inRecipient){
			$this->recipient = $inRecipient;
		}
		
		// Getters and Setters
		public function setMessage($inMessage){
			$this->message = $inMessage;
		}
		
		public function getMessage(){
			return $this->message;
		}
		
		public function setRecipient($inRecipient){
			$this->recipient = $inRecipient;
		}
		
		public function getRecipient(){
			return $this->recipient;
		}
		
		public function setSender($inSender){
			$this->sender = $inSender;
		}
		
		public function getSender(){
			return $this->sender;
		}
		
		public function setSubject($inSubject){
			$this->subject = $inSubject;
		}
		
		public function getSubject(){
			return $this->subject;
		}
		
		// Processing Methods
		public function sendMail(){
			$to = $this->getRecipient();
			$subject = $this->getSubject();
			$messageTxt = wordwrap($this->getMessage(), 65, "\n", false);
			$headers = 'From: ' . $this->getSender();
 
			return mail($to, $subject, $messageTxt, $headers);
		}
	}
?>
	
	