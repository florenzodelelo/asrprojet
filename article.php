<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

if($utilisateursConnect->isConnect == false){ 
    $_SESSION['notification']['result'] = 'danger';
    $_SESSION['notification']['message'] = 'Vous devez être connecté pour accéder à cette page !';
    header("Location: connexion.php");
    exit();
}

//Récupérer l'id de l'article
if (isset($_GET['id'])) {
    $id_article = $_GET['id'];
    $articleManager = new articleManager($bdd);
    $article = $articleManager->get($id_article);
    //print_r2($id_article);
} else {
    $articleManager = new articleManager($bdd);
    $article = new article;
    $article->hydrate(array());
    //print_r2($article);
}


include_once 'includes/header.inc.php';


if (isset($_POST['submit'])) {
    echo 'le formulaire est posté';
    //print_r2($_POST);
    print_r2($_FILES);

    $article = new article(); //Création d'un nouvel objet article (à gauche), dans la classe article (à droite)
    $article->hydrate($_POST); //On prend l'objet article qu'on vient de créer, et on utilise la méthode hydrate, pour remplir de ce qu'on a reçu par POST.
    $article->setDate(date('Y-m-d'));//On prand l'objet article, et on utilise la méthode setDate (voir article.class.php).

    $publie = $article->getPublie() === 'on' ? 1 : 0;

    $article->setPublie($publie);
    print_r2($article);


    $articleManager = new articleManager($bdd);
    if ($_POST['submit'] == 'update') {
        $articleManager->update($article);
    } else {
        $articleManager->add($article);
    }
    //var_dump($articleManager);
    //Traité l'image
    if ($_FILES['image']['error'] == 0) {
        //rename l'image uploded
        $fileInfos = pathinfo($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . 'image' . $articleManager->get_getLastInsertId() . '.' . $fileInfos['extension']);
    }
    if ($_POST['submit'] == 'update') {
        if ($articleManager->get_result() == true) {
            $_SESSION['notification']['result'] = 'success';
            $_SESSION['notification']['message'] = 'Votre article à été modifié !';
        } else {
            $_SESSION['notification']['result'] = 'danger';
            $_SESSION['notification']['message'] = 'Une erreur est survenu pendant la modification de votre article';
        } 
        } else {
            if ($articleManager->get_result() == true) {
                $_SESSION['notification']['result'] = 'success';
                $_SESSION['notification']['message'] = 'Votre article à été ajouté !';
            } else {
                $_SESSION['notification']['result'] = 'danger';
                $_SESSION['notification']['message'] = 'Une erreur est survenu pendant la création de votre article';
            }
        }

    header("Location: index.php");
    exit();
    } else {
    echo 'aucun formulaire est posté';
 }
?>

<div class="container">
    <div class="row">
        <?php if (isset($_GET['id'])) { ?>
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Florenzo Delelo</h1>
            <p class="lead">Modifier un article</p>
            <ul class="list-unstyled">
                <li>Bootstrap 4.5.0</li>
                <li>jQuery 3.5.1</li>
            </ul>
        </div>
        <?php } else { ?>
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Florenzo Delelo</h1>
            <p class="lead">Ajouter un article</p>
            <ul class="list-unstyled">
                <li>Bootstrap 4.5.0</li>
                <li>jQuery 3.5.1</li>
            </ul>
        </div>
        <?php } ?>
    </div>


    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form id="articleForm" method="POST" action="article.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id_article; ?>" >
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" id="titre" name="titre" class="form-control" value="" placeholder="">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="texte">Le texte de mon article</label>
                        <textarea class="form-control" id="texte" name="texte" rows="3"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="image">L'image de mon article</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                </div>    
                <div class="col-lg-12">
                    <div class="form-group form-check">
                            <label class="form-check-label" for="publie">Est-ce que l'article peut-être publié ?</label>
                            <input type="checkbox" <?php if ($article->getPublie() == true) { ?> checked <?php } ?> class="form-check-input" id="publie" name="publie">
                    </div>
                </div>
                <?php if (isset($_GET['id'])) { ?>
                <button type="submit" id="submit" name="submit" class="btn btn-primary" value="update">Modifier mon article</button>   
                <?php } else { ?>
                <button type="submit" id="submit" name="submit" class="btn btn-primary" value="add">Ajouter mon article</button> 
                <?php } ?>
            </form>
        </div>
    </div>
</div>


<?php
include_once 'includes/footer.inc.php';
?>