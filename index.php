<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

//$articleManager = new articleManager($bdd);
//$newArticle = $articleManager->get(1);
//print_r2($newArticle);

$page = !empty($_GET['p']) ? $_GET['p'] : 1;

$articleManager = new articleManager($bdd);

$nbArticleTotalAPublie = $articleManager->countArticlePublie();
//print_r2($nbArticleTotalAPublie);

$indexDepart = ($page - 1) * nb_article_par_page;

$nbPages = ceil($nbArticleTotalAPublie / nb_article_par_page); //Savoir le nombre de page à créer

$listArticle = $articleManager->getListArticleAAfficher($indexDepart, nb_article_par_page);
//print_r2($listArticle);


//AJOUT DE COMMENTAIRES
//Récupérer l'id de l'article   
if (isset($_POST['submit'])) {
    //print_r2($_POST);

    $commentaires = new commentaires(); //Création d'un nouvel objet commentaires (à gauche), dans la classe commentaire (à droite)
    $commentaires->hydrate($_POST); //On prend l'objet article qu'on vient de créer, et on utilise la méthode hydrate, pour remplir de ce qu'on a reçu par POST.
    //print_r2($commentaires);
    
    $commentairesManager = new commentairesManager($bdd); 
    $commentairesManager->add($commentaires); //On ajoute le commentaire dans la bdd.

            
    if ($commentairesManager->get_result() == true) {
                $_SESSION['notification']['result'] = 'success';
                $_SESSION['notification']['message'] = 'Votre commentaire à été ajouté !';
            } else {
                $_SESSION['notification']['result'] = 'danger';
                $_SESSION['notification']['message'] = "Une erreur est survenu pendant l'ajout de votre commentaire.";
   }
}

if (isset($_POST['delete'])) { //suppresion d'un article et de ses commentaires
    $id_article_del = $_POST[id_article_del]; // on initialise la variable avec le POST du bouton que j'ai créé
    $articleManager = new articleManager($bdd); 
    $articleManager->delete($id_article_del);
            if ($articleManager->get_result() == true) {
                $_SESSION['notification']['result'] = 'success';
                $_SESSION['notification']['message'] = "L'article a été supprimé.";
            } else {
                $_SESSION['notification']['result'] = 'danger';
                $_SESSION['notification']['message'] = "Erreur. L'article ne veut pas partir..";
   }
}
    


include_once 'includes/header.inc.php';
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">FLORENZO DELELO</h1>
            <p class="lead">Cours de PHP - BDD avec bootstrap</p>
            <ul class="list-unstyled">
                <li>Bootstrap 4.5.0</li>
                <li>jQuery 3.5.1</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center mt-5">
            <form class="form-inline" id="searchForm" method="GET" action="recherche.php" >
                <label class="sr-only" for="recherche">Recherche</label>
                <input type="text" class="form-control mb-2 mr-sm-2" id="recherche" placeholder="Rechercher un article" name="search" value="">
                <button type="submit" class="btn btn-primary mb-2" name="submitSearch">Rechercher</button>
            </form>
        </div>
    </div>
    <?php if(isset($_SESSION['notification'])){?>
    <!-- Vérifier la session en cours -->
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-primary" role="alert">
               <?= $_SESSION['notification']['message'] ?>
            </div>
        </div>
    </div>
    <!-- Puis tuer la session, pour ne pas toujours voir la notification -->
    <?php
    unset($_SESSION['notification']);
    }
    ?>
    <div class="row">
        <?php
        foreach ($listArticle as $key => $article) {
            ?>
        <!-- On créer les fiches des articles -->
            <div class="col-md-6">
                <div class="card" style="">
                    <img src="img/image<?= $article->getId(); ?>.jpg" class="card-img-top" alt="<?= $article->getTitre(); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article->getTitre(); ?></h5>
                        <p class="card-text"><?= $article->getTexte(); ?></p>
                        <a href="#" class="btn btn-primary"><?= $article->getDate(); ?></a>
                        <?php if ($utilisateursConnect->isConnect == true) { ?> <!-- Si l'utilisateur est connecté. -->
                        <a href="article.php?action=update&id=<?= $article->getId(); ?>" class="btn btn-warning">Modifier</a> <!-- On peut modifier -->
                        <!-- BOUTON DE SUPPRESSION DE L'ARTICLE -->
                        <form id="articleDel" method="POST" action="index.php" enctype="multipart/form-data"> 
                            <input type="hidden" name="id_article_del" value="<?= $article->getId(); ?>" >
                            <button type="submit" id="delete" name="delete" class="btn btn-primary" value="add">Supprimer cet article</button>
                        </form>
                        <!-- FORMULAIRE DES COMMENTAIRES -->
                        <form id="articleComm" method="POST" action="index.php" enctype="multipart/form-data"> <!-- ainsi qu'ajouter un commentaire via se formulaire -->
                        <input type="hidden" name="id_article" value="<?= $article->getId(); ?>" > <!-- On récupère l'id de l'article dans la variable "id article" pour pouvoir récupérer les commentaires correspondant à l'article via une jointure. -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="titre">Pseudo</label>
                                <input type="text" id="pseudo" name="pseudo" class="form-control" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="titre">E-M@IL</label>
                                <input type="email" id="email" name="email" class="form-control" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="texte">Commentaire</label>
                                <textarea class="form-control" id="commentaire" name="commentaire" rows="2"></textarea>
                            </div>
                        </div>
                        <button type="submit" id="submit" name="submit" class="btn btn-primary" value="add">Poster mon commentaire</button> 
                        </form>
                        <!-- FIN DU FORMULAIRE -->                        
                        <!-- On affiche les commentaires même si l'utilisateur est connecté -->
                        <h2 class="card-title"> Commentaires </h2>
                        <?php 
                            $commentaires = new commentaires(); //Tout en restant dans la boucle, je crée ce nouvel objet pour accéder à ce qui est dans la class commentaire (où sera stocké le pseudo, commentaire, etc : grâce à la boucle, un à la fois);
                            $commentairesManager = new commentairesManager($bdd); // Puis cet objet pour accéder à la bdd (pour récupérer les commentaires dans la bdd);
                            $article_id = $article->getId(); //Je crée cette variable qui aura l'id de l'article en cours dans la boucle
                            $listCommentaires = $commentairesManager->getList($article_id); //Puis je vais chercher ma fonction dans commentaire manager avec l'id de l'article en cours dans la boucle comme paramètre
                            foreach ($listCommentaires as $key => $commentaires) { //Enfin, je crée une boucle pour que les éléments soit hydratés les un après les autres.
                            ?>
                            
                            <h3 class="card-title">pseudo : <?= $commentaires->getPseudo(); ?></h3>
                            <p class="card-text">commentaire : </br> <?= $commentaires->getCommentaire(); ?> </p>
                            <?php
                            }
                            //print_r2($commentaires);
                        ?>

                        </div>                 
                        <?php       
                              }
                              if ($utilisateursConnect->isConnect == false) { ?> <!-- si l'utlisateur n'est pas connecté -->
                        <a href="connexion.php" class="btn btn-primary">Vous devez vous connecter pour commenter ou modifier.</a> <!-- On l'invite à se connecter pour ajouter un commentaire ou modifier -->
                        <!-- On affiche les commentaires même si l'utilisateur est déconnecté -->
                        <h2 class="card-title"> Commentaires </h2>
                        <?php 
                            $commentaires = new commentaires(); //Tout en restant dans la boucle, je crée ce nouvel objet pour accéder à ce qui est dans la class commentaire (où sera stocké le pseudo, commentaire, etc : grâce à la boucle, un à la fois);
                            $commentairesManager = new commentairesManager($bdd); // Puis cet objet pour accéder à la bdd (pour récupérer les commentaires dans la bdd);
                            $article_id = $article->getId(); //Je crée cette variable qui aura l'id de l'article en cours dans la boucle
                            $listCommentaires = $commentairesManager->getList($article_id); //Puis je vais chercher ma fonction dans commentaire manager avec l'id de l'article en cours dans la boucle comme paramètre
                            foreach ($listCommentaires as $key => $commentaires) { //Enfin, je crée une boucle pour que les éléments soit hydratés les un après les autres.
                            ?>
                            
                            <h3 class="card-title">pseudo : <?= $commentaires->getPseudo(); ?></h3>
                            <p class="card-text">commentaire : </br> <?= $commentaires->getCommentaire(); ?> </p>
                            <?php
                            }
                            //print_r2($commentaires);
                        ?>
                        <?php } ?>     
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
    <div class="row mt-3">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                   <?php
                    for ($index = 1; $index <= $nbPages; $index++) {
                   ?>
                <li class="page-item <?php if($page == $index){ ?> active <?php } ?>">
                    <a class="page-link" href="index.php?p=<?= $index ?>"><?= $index ?></a></li>
                    <?php
                    }
                   ?>
            </ul>
        </nav>
    </div>
</div>

<?php
include_once 'includes/footer.inc.php';
?>
