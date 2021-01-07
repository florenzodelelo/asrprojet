<?php
//On déclare la class
class article {

    //propriétés de l'objet. (Les propriétés décrivent un objet comme les adjectifs décrivent un nom.). Elles sont remplis par les données reçus. 
    public $id; //Champs en "public" : on peut l'appeler depuis n'importe où dans notre programme.
    public $titre;
    public $texte;
    public $date;
    public $publie;

    public function __construct() { //La class "article" n'accède pas à la base de données, donc aucune construction de PDO.
        
    }
    
    //Get on appelle. Set on défini.

    function getId() { //Création de la fonction getID.
        return $this->id; //La fonction "return" retourne la valeur stocké dans l'objet représenté par $this. On retourne donc la propriété contenue dans l'objet id.
    }

    function getTitre() {
        return $this->titre;
    }

    function getTexte() {
        return $this->texte;
    }

    function getDate() {
        return $this->date;
    }

    function getPublie() {
        return $this->publie;
    }

    function setId($id): void { //Création d'une fonction. Le paramètre possible pour setId est $id.
        $this->id = $id; //dans id ($this->id) on va mettre ce qui est reçu dans $id. $this représente l'objet qu'on utilise (en l'occurence l'objet id, voir plus haut "public $id;").
    }

    function setTitre($titre): void {
        $this->titre = $titre;
    }

    function setTexte($texte): void {
        $this->texte = $texte;
    }

    function setDate($date): void { //Création d'une fonction. Le paramètre possible pour setDate est $date.
        $this->date = $date; // dans l'objet date (réprésenté par $this) on va mettre ce qui est reçu dans $date.
    }

    function setPublie($publie): void {
        $this->publie = $publie;
    }

    // On va créer l'hydratation : permettant de donners des valeurs (des données) aux objets.
    public function hydrate($donnees) { //Création de la fonction hydrate qui reçoit le paramètre $donnees.
        if (isset($donnees['id'])) { //isset = si n'est pas vide. 
            $this->id = $donnees['id']; //l'objet id sera rempli par les données reçu pour id, selon le paramètre $donnees. 
        } else {
            $this->id = ''; //si isset n'est pas satisfait alors on laisse l'objet vide.
        }

        if (isset($donnees['titre'])) {
            $this->titre = $donnees['titre'];
        } else {
            $this->titre = '';
        }

        if (isset($donnees['texte'])) {
            $this->texte = $donnees['texte'];
        } else {
            $this->texte = '';
        }

        if (isset($donnees['date'])) {
            $this->date = $donnees['date'];
        } else {
            $this->date = '';
        }

        if (isset($donnees['publie'])) {
            $this->publie = $donnees['publie'];
        } else {
            $this->publie = '';
        }
    }

}
