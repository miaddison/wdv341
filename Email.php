<?php

	class Email{
		// Variables
		private $message;	// content for body of email
		private $recipient;	// send to email address
		private $sender;	// from email address
		private $subject;	// content for subject line
		
		// Constructor method goes here
		public function __construct($inRecipient){
			//setRecipient($inRecipient); // set property value 
			// above doesn't work cause top to bottom language
			$this->recipient = $inRecipient;
		}
		//public function __construct(){ 	// Can't do this
			
		//}
		
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
		public sendMail(){
			
		}
		
	}
?>
	
	