<?php

namespace Hcode;

use Rain\Tpl;

class Mailer{


	private $mail;

    public function __construct($toAndress, $toName, $subject, $tplName, $data = array()){

		$json = file_get_contents($_SERVER['DOCUMENT_ROOT']."/Mailer.json");
	
		$dados = (array)json_decode($json);

		//echo $dados["USERNAME"];exit;

		//print_r($data);exit;
	
		$user = $dados["USERNAME"];
        $password = $dados["PASSWORD"];
        $name = "Hcode Store";
        

        $config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false
	    );

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		$this->mail = new \PHPMailer();

		$this->mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		//Tell PHPMailer to use SMTP
		$this->mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->mail->SMTPDebug = 0;

		//Ask for HTML-friendly debug output
		$this->mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		$this->mail->Host = 'smtp.gmail.com';
		// use
		// $this->mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$this->mail->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		$this->mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$this->mail->SMTPAuth = true;

		//user to use for SMTP authentication - use full email address for gmail
		$this->mail->Username = $user;

		//Password to use for SMTP authentication
		$this->mail->Password = $password;

		//Set who the message is to be sent from
		$this->mail->setFrom($user, $name);

		//Set an alternative reply-to address
		//$this->mail->addReplyTo('replyto@example.com', 'First Last');

		//Set who the message is to be sent to
		$this->mail->addAddress($toAndress, $toName);

		//Set the subject line
		$this->mail->Subject = $subject;

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->mail->msgHTML($html,__DIR__);

		//Replace the plain text body with one created manually
		$this->mail->AltBody = 'This is a plain-text message body';


    }

    public function send(){

		return $this->mail->send();

	}

}