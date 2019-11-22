<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

/**
 * Post form data
 * **************
 *
 * Text Boxes
 * prename
 * surname
 * adress
 * phone
 * customer_mail
 * notes
 * mitnahme
 *
 * Check Boxes
 * desktop, laptop, tablet, smartphone
 *
 * Text Box
 * marke
 *
 * Check Boxes
 * netzteil, tastatur, maus, bildschirm, festplatte, usbstick
 *
 * Text Box
 * sonstiges
 * canvas_img_data
 */
$inputData = $_POST;

if (!isset($inputData ["customer_mail"])) {
  readfile("html/index.html");
  return;
}


$curDate = date("r");
$prename = $inputData ["prename"];
$surname = $inputData ["surname"];
$adress = $inputData ["adress"];
$phone = $inputData ["phone"];
$customer_mail = $inputData ["customer_mail"];
$notes = $inputData ["notes"];
$mitnahme = $inputData ["mitnahme"];
$marke = $inputData ["marke"];

//Collect machine data
$machineList = "";
$machineChecks = array(
  "desktop" => "Desktop-Computer",
  "laptop" => "Laptop",
  "smartphone" => "Smartphone",
  "tablet" => "Tablet"
);
foreach($machineChecks as $machine => $text) {
  if (isset($inputData [$machine]))
    $machineList .= "$text, ";
}
$machineList .= $inputData ["sonstiges"];

//Collect device data
$deviceList = "";
$deviceChecks = array(
  "netzteil" => "Netzteil",
  "tastatur" => "Tastatur",
  "maus" => "Maus",
  "bildschirm" => "Bildschirm",
  "festplatte" => "externe Festplatte",
  "usbstick" => "CD / USB-Stick",
);
foreach($deviceChecks as $device => $text) {
  if (isset($inputData [$device]))
    $deviceList .= "$text, ";
}
$deviceList .= $inputData ["sonstiges"];

//Make Signature image
$imgName = time();
$canvas_img_data = $inputData ["canvas_img_data"];
list(, $canvas_img_data) = explode(',', $canvas_img_data);
$canvas_img_data = base64_decode($canvas_img_data);
file_put_contents("signature/$imgName.png", $canvas_img_data);

$sigImageHtml = "<img src='http://Your-Server-URL/signature/$imgName.png'/>";

$emailContent = "Geschätzter Kunde <br>
<br>
Vielen Dank für Ihren Auftrag. Wir haben heute, ${curDate}, folgende(s) Gerät(e) bei Ihnen mitgenommen: <br>
<br>
{$machineList} <br>
<br>
Marke/Modell: ${marke} <br>
<br>
Grund der Mitnahme: ${mitnahme} <br>
<br>
Zubehör: ${deviceList} <br>
<br>
Mit Ihrer Unterschrift haben Sie bereits die dieser Email angehängten Allgemeinen Geschäftsbedingungen akzeptiert. <br>
<br>
Ihre Unterschrift: ${sigImageHtml} <br>
<br>
Ihre Personalien: <br>
Vorname: ${prename} <br>
Name:* ${surname} <br>
Adresse: ${adress} <br>
Telefon: ${phone} <br>
E-Mail:* ${customer_mail} <br>
Bemerkung: ${notes}\n <br>
<br>
Ihr Ansprechpartner ist: Claude Vital <br>
<br>
Wir bedanken uns für Ihr Vertrauen. <br>
<br>
Freundliche Grüsse <br>
<br>
computerservice24.ch <br>
<br>
Vital Media Design <br>
Terrassenweg 116 <br>
4625 Oberbuchsiten <br>
Tel.: 062 552 00 24";

$emailContent = str_replace('\n', '<br>', $emailContent);


$mail = new PHPMailer(true);
$mail->CharSet = "UTF-8";

try {
  //Server settings
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
  $mail->isSMTP(true);                                            // Send using SMTP
  $mail->Host       = 'mail.your-server.de';            // Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
  $mail->Username   = 'signature@computerservice24.ch';       // SMTP username
  $mail->Password   = 'c77PGh3Ew81K5xJH';                     // SMTP password
  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
  $mail->Port       = 587;                                    // TCP port to connect to

  //Recipients
  $mail->setFrom('help@computerservice24.ch', 'Computer Service 24');
  $mail->addAddress($customer_mail, $surname);     // Add a recipient
  $mail->addAddress('help@computerservice24.ch'); // Name is optional
  // $mail->addReplyTo('help@computerservice24.ch', 'Computer Service 24');
  // $mail->addCC('help@computerservice24.ch');
  // $mail->addBCC('bcc@example.com');

  // Attachments
  $mail->addAttachment('attachments/AGB_Vital_Media_Design.pdf');         // Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

  // Content
  $mail->isHTML(true);                                  // Set email format to HTML
  $mail->Subject = 'Computer Service 24';
  $mail->Body    = $emailContent;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();
  echo 'Vielen Dank. Alles in Ordnung.';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

return;

readfile("html/index.html");
