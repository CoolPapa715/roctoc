<?php
require_once(__DIR__ . '/../inc/config.php');

// init vars
$form_submitted = 0;
$invalid_email  = 0;
$request_sent   = 0;
$user_exists    = 0;
$mailer_problem = 0;

// initialize swiftmailer
$transport_smtp = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port)
	->setUsername($smtp_user)
	->setPassword($smtp_pass);
$mailer = Swift_Mailer::newInstance($transport_smtp);

// check if form submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$form_submitted = 1;
	$email = !empty($_POST['email']) ? $_POST['email'] : '';

	// validate email
	if(!Swift_Validate::email($email)){ //if email is not valid
		$invalid_email = 1;
	}
}

// if all fields submitted
if(!empty($email) && !$invalid_email) {
	$empty_fields = 0;

	// user ip
	$ip = get_ip();

	// check if exists
	$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$count = $stmt->fetchColumn();

	// user exists?
	if($count > 0) {
		// get user id
		$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_id = $row['id'];

		// get confirmation string
		$stmt = $conn->prepare("SELECT confirm_str FROM signup_confirm WHERE user_id = :user_id");
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$confirm_str = $row['confirm_str'];

		if(empty($confirm_str)) {
			$confirm_str = generatePassword(16);

			// insert confirmation string into db
			$stmt = $conn->prepare('
			INSERT INTO signup_confirm(
				user_id,
				confirm_str,
				created
				)
			VALUES(
				:user_id,
				:confirm_str,
				NOW()
				)
			');

			$stmt->bindValue(':user_id'    , $user_id);
			$stmt->bindValue(':confirm_str', $confirm_str);
			$stmt->execute();
		}

		$confirm_link = $baseurl . "/user/register-confirm/" . $user_id . "," . $confirm_str;

		// email user
		$query = "SELECT * FROM email_templates WHERE type = 'signup_confirm'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body    = $row['body'];

		// string replacements
		$email_subject = str_replace('%site_name%', $site_name, $email_subject);
		$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
		$email_body = str_replace('%site_name%', $site_name, $email_body);
		$email_body = str_replace('%site_url%', $baseurl, $email_body);
		$email_body = str_replace('%confirm_link%', $confirm_link, $email_body);

		$message = Swift_Message::newInstance()
			->setSubject($email_subject)
			->setFrom(array($admin_email => $site_name))
			->setTo($email)
			->setBody($email_body)
			->setReplyTo($admin_email)
			->setReturnPath($admin_email)
			;

		// Send the message
		try {
			$mailer->send($message);
			$request_sent = 1;
		}

		catch(exception $e) {
			$exception = utf8_encode ($e->getMessage());
			$mailer_problem = 1;
		}

		$user_exists  = 1;
	}
}

/*--------------------------------------------------
Canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/resend-confirmation';
