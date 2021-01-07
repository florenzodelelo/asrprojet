<?php

class commentairesManager {

    //DECLARATIONS ET INSTANCIATIONS
    //propriétés de l'objet. (Les propriétés décrivent un objet comme les adjectifs décrivent un nom.)
    //Champs "private" : on peut l'appeler uniquement depuis l'intérieur de "class articleManager". On va y mettre le CRUD de la BDD (Create, Read, Update, Delete).
    private $bdd; //Instance de PDO
    private $_result; //Le résultat du commentaire (éhec ou réussite, pour un ajout par exemple).
    private $_message;
    private $_commentaire;
    private $_getLastInsertId;
    
    public function __construct(PDO $bdd) { //On initialise l'objet en lui indiquant qu'on va accéder à une base de données (PDO), puis les infos d'accès sont dans $bdd, qui est importé dans les pages "%.php" nécéssitant l'accès à la BDD grâce à include_once bdd.conf.php.
        $this->setBdd($bdd); //$this repésente la fonction setBdd, créée plus bas. On envoi les paramètres définis dans construct dans le paramètre $bdd attendu par setBdd.
    }

    function getBdd() {
        return $this->bdd; //this représente l'objet bdd. On retourne le contenu de l'objet bdd. La fonction setBdd se charge de remplir l'objet.
    }
       
    function get_result() {
        return $this->_result;
    }
    
    function get_message() {
        return $this->_message;
    }
    
        function get_commentaire() {
        return $this->_commentaire;
    }
    
    function setBdd($bdd): void {
        $this->bdd = $bdd; //this représente l'objet bdd. Il est rempli par le contenu reçu pour le paramètre $bdd : déjà initialisé plus haut par construct (ligne 15).
    }

    function set_result($_result): void {
        $this->_result = $_result;
    }
    
    function set_message($_message): void {
        $this->_message = $_message;
    }
    
    function set_commentaire($_commentaire): void {
        $this->_commentaire = $_commentaire;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }
   
        public function add(commentaires $commentaires) { //méthode : ajouter un commentaire.
        $sql = "INSERT INTO commentaires "
                . "(pseudo, email, commentaire, id_article) "
                . "VALUES (:pseudo, :email, :commentaire, :id_article)";

        $req = $this->bdd->prepare($sql);
        //secur variable
        $req->bindValue(':pseudo', $commentaires->getPseudo(), PDO::PARAM_STR);
        $req->bindValue(':email', $commentaires->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':commentaire', $commentaires->getCommentaire(), PDO::PARAM_STR);
        $req->bindValue(':id_article', $commentaires->getId_article(), PDO::PARAM_INT);
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
           
    public function getList($id_article) { //méthode : récupérer la liste des commentaires.
        $listCommentaires = [];

        $sql = 'SELECT pseudo, commentaire FROM commentaires INNER JOIN articles ON commentaires.id_article = articles.id WHERE articles.id = :id'; // On prépare la requête : on demande pseudo et commentaire dans la table commentaires quand l'id_article de la table commentaires correspond à l'id d'un article, avec une jointure donc entre la table commentaires et article.
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':id', $id_article, PDO::PARAM_INT);
        //execute la requête
        $req->execute();

        //On stocke les données reçues dans un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $commentaires = new commentaires();
            $commentaires->hydrate($donnees);
            $listCommentaires[] = $commentaires;
        }
        //print_r2($listCommentaires);
        return $listCommentaires;
    }
}
