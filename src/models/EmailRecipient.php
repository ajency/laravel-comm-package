<?php 
namespace Ajency\Comm\Models;

class EmailRecipient{
	const TYPE = 'email';
	private $to = [];
	private $cc = [];
	private $bcc = [];
	private $attachments = [];
	private $params = [];

	public function setTo($to){
		$this->to = (is_array($to))? $to : [$to];
	}
	public function setCc($cc){
		$this->cc = (is_array($cc))? $cc:[$cc];
	}
	public function setBcc($bcc){
		$this->bcc = (is_array($bcc))? $bcc:[$bcc];
	}
	public function setAttachments($attachments){
		$this->attachments = (is_array($attachments))? $attachments:[$attachments];
	}
	public function setParams($params){
		$this->params = (is_array($params))? $params:[$params];
	}

	public function getTo(){
		return $this->to ;
	}
	public function getCc(){
		return $this->cc ;
	}
	public function getBcc(){
		return $this->bcc ;
	}
	public function getAttachments(){
		return $this->attachments ;
	}
	public function getParams(){
		return $this->params ;
	}

	public function addTo($to){
		$this->to[] = $to;
	}
	public function addCc($cc){
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

