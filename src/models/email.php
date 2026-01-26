<?php
/**
 * Fonctions utilitaires pour envoyer des mails
 * Utilise sendmail (serveur IUT)
 */


function sendMail($to, $subject, $messageBody, $from = null) {

    // Charger la configuration
    $config = require __DIR__ . '/mail.php';
    


    // Préparer les headers
    $fromEmail = $from ?? $config['from_email'];
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset={$config['charset']}\r\n";
    $headers .= "From: {$config['from_name']} <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$fromEmail}\r\n";

    // Envoi de l'email
    $success = mail($to, $subject, $messageBody, $headers);

    return $success;
}

/**
 * Envoyer un mail de remboursement pour ressource perdue
 */
function sendLostPassword($userEmail, $userName, $userPassword) {
    $subject = "Mot de passe oublié gamehub";

    $message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
</head>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
        <div style='background: #5a67d8; color: white; padding: 20px; border-radius: 8px 8px 0 0;'>
            <h2 style='margin: 0;'>GameHub - Mot de passe oublié</h2>
        </div>
        <div style='background: #f7fafc; padding: 20px; border-radius: 0 0 8px 8px;'>
            <p>Bonjour $userName,</p>
            <p>Voici votre nouveau mot de passe :</p>
            <p><strong>$userPassword</strong></p>
        </div>
        <div style='text-align: center; color: #718096; font-size: 12px; margin-top: 20px;'>
            <p>Cet email a été généré automatiquement. Veuillez ne pas y répondre directement.</p>
        </div>
    </div>
</body>
</html>";

    return sendMail($userEmail, $subject, $message);
}

?>
