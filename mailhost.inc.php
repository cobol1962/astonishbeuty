<?
$mail = new PHPMailer();
$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtp.everyone.net";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "sendmail@americanhospitalityacademy.com";  // SMTP username
$mail->Password = '$en)M@1l'; // SMTP password
$mail->From = "no-reply@ahaworldcampus.com";
$mail->FromName = "AHA World Campus";
$mail->WordWrap = 100;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
?>