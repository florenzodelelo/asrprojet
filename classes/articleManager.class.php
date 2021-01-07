<?php

class articleManager {

    //DECLARATIONS ET INSTANCIATIONS
    //propriétés de l'objet. (Les propriétés décrivent un objet comme les adjectifs décrivent un nom.)
    //Champs "private" : on peut l'appeler uniquement depuis l'intérieur de "class articleManager". On va y mettre le CRUD de la BDD (Create, Read, Update, Delete).
    private $bdd; //Instance de PDO
    private $_result; //Le résultat de l'article (éhec ou réussite, pour un ajout par exemple).
    private $_message;
    private $_article;
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

    function get_article() {
        return $this->_article;
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

    function set_article($_article): void {
        $this->_article = $_article;
    }

    public function get($id) { //méthode : récupérer les articles selon son id.
        $sql = 'SELECT * FROM articles WHERE id = :id';
        $req = $this->bdd->prepare($sql);

        //securisé la requête
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $article = new article();
        $article->hydrate($donnees);

        return $article;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function getList() { //méthode : récupérer la liste des articles.
        $listArticle = [];

        //Prepare requête sql sans limite
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles';
        $req = $this->bdd->prepare($sql);

        //execute la requête
        $req->execute();


        //On stocke les données reçues dans un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }
        //print_r2($listeArticle);
        return $listArticle;
    }

    public function add(article $article) { //méthode : ajouter un article.
        $sql = "INSERT INTO articles "
                . "(titre, texte, publie, date) "
                . "VALUES (:titre, :texte, :publie, :date)";

        $req = $this->bdd->prepare($sql);
        //secur variable
        $req->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
        $req->bindValue(':texte', $article->getTexte(), PDO::PARAM_STR);
        $req->bindValue(':publie', $article->getPublie(), PDO::PARAM_INT);
        $req->bindValue(':date', $article->getDate(), PDO::PARAM_STR);
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
    
    public function countArticlePublie() { //méthode : compter le nombre d'articler à afficher (quand publie = 1).
        $sql = "SELECT COUNT(*) as total FROM articles "
                . "WHERE publie = 1";
        $req = $this->bdd->prepare($sql);
        $req->execute();
        $count = $req->fetch(PDO::FETCH_ASSOC);
        $total = $count['total'];
        return $total;
    }
        public function getListArticleAAfficher($depart, $limit) { //méthode : récupérer les articles à afficher (quand publie = 1).
        $listArticle = [];

        //Prepare requête sql sans limite
        $sql = 'SELECT id,'
                . 'titre,'
                . 'texte,'
                . 'publie,'
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'LIMIT :depart, :limit';
        $req = $this->bdd->prepare($sql);
        $req->bindValue (':depart', $depart, PDO::PARAM_INT); //Sécurisation des variables
        $req->bindValue (':limit', $limit, PDO::PARAM_INT);

        //execute la requête
        $req->execute();


        //On stocke les données reçues dans un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }
        
        
        //print_r2($listeArticle);
        return $listArticle;
    } 
    
        // Fonction de mise à jour d'un article via son ID
    public function update(article $article) { //méthode : mettre à jour des articles (selon son id).
        //print_r2($article);
        $sql = "UPDATE articles SET "
                . "titre = :titre, texte = :texte, publie = :publie "
                . "WHERE id = :id";
        $req = $this->bdd->prepare($sql);
        // Sécurisation des variables
        $req->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
        $req->bindValue(':texte', $article->getTexte(), PDO::PARAM_STR);
        $req->bindValue(':publie', $article->getPublie(), PDO::PARAM_INT);
        $req->bindValue(':id', $article->getId(), PDO::PARAM_INT);
        //Exécuter la requête
        $req->execute();
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $article->getId();
        } else {
            $this->_result = false;
        }
        return $this;
    }

    public function getListArticleFromSearch($search) { //méthode : chercher des articles (selon un mot clé dans titre ou texte).
        $listArticle = [];

        // Prépare une requête de type SELECT avec une clause WHERE selon l'id.
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'AND (titre LIKE :recherche '
                . 'OR texte LIKE :recherche)';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':recherche', "%" . $search . "%", PDO::PARAM_STR);

        // Exécution de la requête avec attribution des valeurs aux marqueurs nominatifs.
        $req->execute();

        // On stocke les données obtenues dans un tableau.
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            //On créé des objets avec les données issues de la table
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }

        //print_r2($listArticle);
        return $listArticle;
    }
    
        public function delete($id) { //méthode : supprimer un articles et ses commentaires. 

        $sql = 'DELETE articles.*, commentaires.* FROM articles LEFT JOIN commentaires ON articles.id = commentaires.id_article WHERE articles.id = :id';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        //execute la requête
        $req->execute();
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $this->bdd->lastInsertId();
        } else {
            $this->_result = false;
        }
        return $this;
    }

}
