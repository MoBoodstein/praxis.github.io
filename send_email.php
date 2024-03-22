<?php

require_once 'config.php';

require_once __DIR__ . '/vendor/autoload.php'; // Einbinden der autoload.php-Datei aus dem Vendor-Verzeichnis

// Laden der Umgebungsvariablen aus der .env-Datei
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mail senden über die eingebaute mail() Funktion
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $to = "moritz@moritz-lennartz.de"; // Ihre E-Mail-Adresse hier einfügen
    $subject = "Neue Nachricht von " . $name;
    $body = "Name: " . $name . "\n";
    $body .= "E-Mail: " . $email . "\n";
    $body .= "Nachricht: " . $message;

    if (mail($to, $subject, $body)) {
        echo "Die Nachricht wurde erfolgreich gesendet.";
    } else {
        echo "Beim Senden der Nachricht ist ein Fehler aufgetreten.";
    }

    // Mail senden über PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php'; // Pfad zu PHPMailer

    // Instantiieren Sie die PHPMailer-Klasse
    $mail = new PHPMailer(true);

    try {
        // Server-Einstellungen
        $mail->SMTPDebug = 2; // Aktivieren Sie Debugging für detaillierte Ausgaben
        $mail->isSMTP(); // Verwenden Sie SMTP
        $mail->Host = $_ENV['SMTP_HOST']; // Setzen Sie den SMTP-Server für den Versand von E-Mails
        $mail->SMTPAuth = true; // Aktivieren Sie die SMTP-Authentifizierung
        $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP-Benutzername
        $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP-Passwort
        $mail->SMTPSecure = 'tls'; // Aktivieren Sie die Verschlüsselung; 'tls' empfohlen
        $mail->Port = 587; // TCP-Port, über den Sie sich mit dem SMTP-Server verbinden möchten

        // Absender und Empfänger
        $mail->setFrom('von@example.com', 'Ihr Name');
        $mail->addAddress('an@example.net', 'Empfänger Name'); // Fügen Sie Empfänger hinzu

        // Inhalt
        $mail->isHTML(true); // E-Mail-Format: HTML
        $mail->Subject = 'Hier ist der Betreff';
        $mail->Body = 'Dies ist der HTML-Text der E-Mail';

        $mail->send();
        echo 'Die E-Mail wurde erfolgreich gesendet.';
    } catch (Exception $e) {
        echo "E-Mail konnte nicht gesendet werden. Mailer-Fehler: {$mail->ErrorInfo}";
    }
}
?>
