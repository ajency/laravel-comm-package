<?php 
namespace Ajency\Comm\Recipients;

class EmailRecipients{
	const $type = 'email';
	private $to = [];
	private $cc = [];
	private $bcc = [];
	private $attachments = [];
	private $params = [];

	public function setTo($to){
		$this->to = $to;
	}
	public function setCC($cc){
		$this->cc = $cc;
	}
	public function setBcc($bcc){
		$this->bcc = $bcc;
	}
	public function setAttachments($attachments){
		$this->attachments = $attachments;
	}
	public function setParams($params){
		$this->params = $params;
	}

	public function getTo($to){
		return $this->to ;
	}
	public function getCC($cc){
		return $this->cc ;
	}
	public function getBcc($bcc){
		return $this->bcc ;
	}
	public function getAttachments($attachments){
		return $this->attachments ;
	}
	public function getParams($params){
		return $this->params ;
	}

	public function addTo($to){
		$this->to[] = $to;
	}
	public function addCC($cc){
		$this->cc[] = $cc;
	}
	public function addBcc($bcc){
		$this->bcc[] = $bcc;
	}
	public function addAttachments($attachments){
		$this->attachments[] = $attachments;
	}
	public function addParams($params){
		$this->params[] = $params;
	}


}

