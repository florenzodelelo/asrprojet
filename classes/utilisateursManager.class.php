<?php

class utilisateursManager {

    //DECLARATIONS ET INSTANCIATIONS
    private $bdd; //Instance de PDO
    private $_result;
    private $_message;
    private $_utilisateurs;
    private $_getLastInsertId;
    
    public function __construct(PDO $bdd) {
        $this->setBdd($bdd);
    }

    function getBdd() {
        return $this->bdd;
    }

    function get_result() {
        return $this->_result;
    }

    function get_message() {
        return $this->_message;
    }

    function get_utilisateurs() {
        return $this->_utilisateurs;
    }

    function setBdd($bdd): void {
        $this->bdd = $bdd;
    }

    function set_result($_result): void {
        $this->_result = $_result;
    }

    function set_message($_message): void {
        $this->_message = $_message;
    }

    function set_utilisateurs($_utilisateurs): void {
        $this->_utilisateurs = $_utilisateurs;
    }

    public function getByEmail($email) {
        $sql = 'SELECT * FROM utilisateurs WHERE email= :email';
        $req = $this->bdd->prepare($sql);

        //securisé la requête
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $utilisateurs = new utilisateurs();
        $utilisateurs->hydrate($donnees);

        return $utilisateurs;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function getList() {
        $listUtilisateurs = [];

        //Prepare requête sql sans limite
        $sql = 'SELECT id, '
                . 'nom, '
                . 'prenom, '
                . 'email, '
                . 'mdp '
                . 'sid ';
        $req = $this->bdd->prepare($sql);

        //execute la requête
        $req->execute();


        //tant que les resulta son recu on fait un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs = new utilisateurs();
            $utilisateurs->hydrate($donnees);
            $listUtilisateurs[] = $utilisateurs;
        }
        //print_r2($listeutilisateurs);
        return $listUtilisateurs;
    }
    
    public function getBySid($sid) {

        // Prépare une requete de type SELECT
        $sql = 'SELECT * FROM utilisateurs WHERE sid = :sid';
        $req = $this->bdd->prepare($sql);

        //Execution de la requete

        $req->bindValue(':sid', $sid, PDO::PARAM_STR);
        $req->execute();

        // On stocke les données obtenu dans un tableau

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $utilisateurs = new utilisateurs();
        $utilisateurs->hydrate($donnees);
        //print_r2($utilisateurs);
        return $utilisateurs;
    }

    public function add(utilisateurs $utilisateurs) {
        $sql = "INSERT INTO utilisateurs "
                . "(nom, prenom, email, mdp, sid) "
                . "VALUES (:nom, :prenom, :email, :mdp, :sid)";

        $req = $this->bdd->prepare($sql);
        //securisation variable
        $req->bindValue(':nom', $utilisateurs->getNom(), PDO::PARAM_STR);
        $req->bindValue(':prenom', $utilisateurs->getPrenom(), PDO::PARAM_STR);
        $req->bindValue(':email', $utilisateurs->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':mdp', password_hash($utilisateurs->getMdp(), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $req->bindValue(':sid', $utilisateurs->getSid(), PDO::PARAM_STR);
        //exec sql
        $req->execute();

        //retour erreur
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $this->bdd->lastInsertId();
        } else {
            $this->_result = false;
        }
        return $this;
    }
    
    public function updateByEmail(utilisateurs $utilisateurs) {
        $sql = "UPDATE utilisateurs SET sid = :sid WHERE email = :email";
        $req = $this->bdd->prepare($sql);
        //securisation variable
        $req->bindValue(':email', $utilisateurs->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':sid', $utilisateurs->getSid(), PDO::PARAM_STR);
        //Exécuter la requête
        $req->execute();
        if ($req->errorCode() == 00000) {
            $this->_result = true;
        } else {
            $this->_result = false;
        }
        return $this;
    }

}
