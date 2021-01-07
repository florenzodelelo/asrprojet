<?php

class utilisateurs {

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mdp;
    public $sid;

    public function __construct() {
        
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getEmail() {
        return $this->email;
    }
    
    function getMdp() {
        return $this->mdp;
    }
    
    function getSid() {
        return $this->sid;
    }

    function setId($id): void {
        $this->id = $id;
    }

    function setNom($nom): void {
        $this->nom = $nom;
    }

    function setPrenom($prenom): void {
        $this->prenom = $prenom;
    }

    function setEmail($email): void {
        $this->email = $email;
    }
    
    function setMdp($mdp) : void {
        $this->mdp = $mdp;
    }

    function setSid($sid): void {
        $this->sid = $sid;
    }

    public function hydrate($donnees) {
        if (isset($donnees['id'])) {
            $this->id = $donnees['id'];
        } else {
            $this->id = '';
        }

        if (isset($donnees['nom'])) {
            $this->nom = $donnees['nom'];
        } else {
            $this->nom = '';
        }

        if (isset($donnees['prenom'])) {
            $this->prenom = $donnees['prenom'];
        } else {
            $this->prenom = '';
        }

        if (isset($donnees['email'])) {
            $this->email = $donnees['email'];
        } else {
            $this->email = '';
        }
        
        if (isset($donnees['mdp'])) {
            $this->mdp = $donnees['mdp'];
        } else {
            $this->mdp = '';
        }

        if (isset($donnees['sid'])) {
            $this->sid = $donnees['sid'];
        } else {
            $this->sid = '';
        }
    }

}
