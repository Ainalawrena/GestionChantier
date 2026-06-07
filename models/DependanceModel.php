<?php
class DependanceModel {
    private $pdo; // connexion BDD

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
    }  



}