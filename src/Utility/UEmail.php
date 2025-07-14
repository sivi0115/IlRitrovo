<?php
namespace Utility;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use View\VEmail;

class UEmail {

    public static function sendConfirmation(string $to, array $data, ?int $idTable): bool {
        $mail = new PHPMailer(true);
        $view=new VEmail();
        try {
            // Configurazione SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Cambia col tuo SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'marcociprianituna2000@gmail.com';  // Configurazione da config.php o .env
            $mail->Password   = 'agpejyafvqqqphda';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            // Mittente e destinatario
            $mail->setFrom('marcociprianituna2000@gmail.com', 'Il Ritrovo');
            $mail->addAddress($to);
            // Contenuto
            if ($idTable !== null) {
                $mail->Subject = 'Conferma Prenotazione Tavolo - Il Ritrovo';
                $bodyHtml=$view->showTablesEmail($data);
                $mail->Body=$bodyHtml;
                $mail->AltBody = strip_tags($bodyHtml);
            } else {
                $mail->Subject = 'Conferma Prenotazione Stanza - Il Ritrovo';
                $bodyHtml=$view->showRoomsEmail($data);
                $mail->Body=$bodyHtml;
                $mail->AltBody = strip_tags($bodyHtml);
            }
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Errore invio email: {$mail->ErrorInfo}");
            return false;
        }
    }
}