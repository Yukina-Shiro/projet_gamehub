<?php
/**
 * Configuration pour l'envoi d'emails
 * Utilise sendmail (serveur IUT)
 */

return [
    // Informations de l'expéditeur
    'from_email' => 'noreply@unice.fr',
    'from_name' => 'gamehub ',

    // Charset
    'charset' => 'UTF-8',

    // Mode simulation (si true, les emails ne sont pas vraiment envoyés, juste loggés)
    'simulate' => false,
];
?>