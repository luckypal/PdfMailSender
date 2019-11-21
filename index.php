<?php

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

require 'vendor/Mail.php';

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
$canvas_img_data = $inputData ["canvas_img_data"];
$sigImageHtml = "<img src='$canvas_img_data}'/>";

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

$result = mail("bigluckypal@gmail.com", "hello", $emailContent);
var_dump($result);
return;


// $params = array();
// $mailer = Mail::factory('smtp', $params);
// $e = $mailer->send("bigluckypal@gmail.com", array(
//   "Subject" => "From Computer service 24",
//   "Content-Type" => "text/html; charset=UTF-8\r\n"
// ), $emailContent);

// if (is_a($e, 'PEAR_Error')) {
//   $err = $e->getMessage();
//   print_r($err);
//   if (preg_match('/Failed to connect to bogus.host.tld:25 \[SMTP: Failed to connect socket:.*/i', $err)) {
//      echo "OK";
//   }
// }

readfile("html/index.html");
