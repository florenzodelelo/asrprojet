<?php
//On déclare la class
class commentaires {
    
    //propriétés de l'objet. (Les propriétés décrivent un objet comme les adjectifs décrivent un nom.). Elles sont remplis par les données reçus. 
    public $id; //Champs en "public" : on peut l'appeler depuis n'importe où dans notre programme.
    public $pseudo;
    public $email;
    public $commentaire;
    public $id_article;

    public function __construct() { //La class "article" n'accède pas à la base de données, donc aucune construction de PDO.
        
    }
 //Get on appelle. Set on défini.

    function getId() { //Création de la fonction getID.
        return $this->id; //La fonction "return" retourne la valeur stocké dans l'objet représenté par $this. On retourne donc la propriété contenue dans l'objet id.
    }

    function getPseudo() {
        return $this->pseudo;
    }
    
    function getEmail() {
        return $this->email;
    }

    function getCommentaire() {
        return $this->commentaire;
    }   
    
    function getId_article() {
        return $this->id_article;
    } 
    
    function setId($id): void { //Création d'une fonction. Le paramètre possible pour setId est $id.
        $this->id = $id; //dans id ($this->id) on va mettre ce qui est reçu dans $id. $this représente l'objet qu'on utilise (en l'occurence l'objet id, voir plus haut "public $id;").
    }

    function setPseudo($pseudo): void {
        $this->pseudo = $pseudo;
    }
    
    function setEmail($email): void {
        $this->email = $email;
    }

    function setCommentaire($commentaire): void {
        $this->commentaire = $commentaire;
    }
    
    function setId_article($id_article): void {
        $this->id_article = $id_article;
    }
    
    // On va créer l'hydratation : permettant de donners des valeurs (des données) aux objets.
    public function hydrate($donnees) { //Création de la fonction hydrate qui reçoit le paramètre $donnees.
        if (isset($donnees['id'])) { //isset = si n'est pas vide. 
            $this->id = $donnees['id']; //l'objet id sera rempli par les données reçu pour id, selon le paramètre $donnees. 
        } else {
            $this->id = ''; //si isset n'est pas satisfait alors on laisse l'objet vide.
        }

        if (isset($donnees['pseudo'])) {
            $this->pseudo = $donnees['pseudo'];
        } else {
            $this->pseudo = '';
        }
        
        if (isset($donnees['email'])) {
            $this->email = $donnees['email'];
        } else {
            $this->email = '';
        }

        if (isset($donnees['commentaire'])) {
            $this->commentaire = $donnees['commentaire'];
        } else {
            $this->commentaire = '';
        }
        
        if (isset($donnees['id_article'])) {
            $this->id_article = $donnees['id_article'];
        } else {
            $this->id_article = '';
        }
}
}