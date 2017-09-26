<?php
	class Email{
		private $recipient; 
		private $sender;
		private $subject;
		private $message;

		public function __construct($inRecipient){
			$this->setRecipient($inRecipient);
		}
		
		public function setRecipient($inRecipient){
			$this->recipient = $inRecipient;
		}
		
		public function getRecipient(){
			return $this->recipient;
		}
		
		public funcion setSender($inSender){
			$this->sender = $inSender;
		}

		public function getSender(){
			return $this->sender;
		}
		
		public funcion setSubject($inSubject){
			$this->subject = $inSubject;
		}

		public function getSubject(){
			return $this->subject;
		}
		
		public funcion setMessage($inMessage){
			$this->message = $inMessage;
		}

		public function getMessage(){
			return $this->message;
		}

		public function sendMail(){
			$to = $this->getRecipient();
			$subject = $this->getSubject();
			$messageTxt = wordwrap(this->getMessage(), 65, "\n", false);
			$headers = 'From: ' . $this->getSender();
 
			return mail($to, $subject, $messageTxt, $headers);
		}
	}		
?>