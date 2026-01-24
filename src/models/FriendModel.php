<?php
require_once 'models/Model.php';

class FriendModel extends Model {

    // Envoie une demande d'ami
    public function sendRequest($fromId, $toId) {
        // À REMPLIR : INSERT INTO Amis...
    }

    // Accepte une demande
    public function acceptRequest($senderId, $myId) {
        // À REMPLIR : UPDATE Amis SET statut = 'valide'...
    }

    // Liste les demandes en attente (pour l'espace requête)
    public function getPendingRequests($myId) {
        // À REMPLIR : SELECT WHERE statut = 'attente'...
        return [];
    }
}
?>