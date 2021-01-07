<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';
//print_r2($_SESSION);
//$article = new article();
//$article->hydrate(array());
//print_r2($article);
//print_r2($_GET);
if (isset($_GET['submitSearch'])) {
    $search = $_GET['search'];
    $articleManager = new articleManager($bdd);
    $listArticle = $articleManager->getListArticleFromSearch($search);
    //print_r2($listeArticle);
//exit();
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
        <?php if (isset($_SESSION['notification'])) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-<?= $_SESSION['notification']['result'] ?>" role="alert">
                        <?= $_SESSION['notification']['message'] ?>
                    </div>
                </div>
            </div>
            <?php
            unset($_SESSION['notification']);
        }
        ?>

        <div class="row">
            <?php
            foreach ($listArticle as $key => $article) {
                ?>
                <div class="col-md-6">
                <div class="card" style="">
                    <img src="img/image<?= $article->getId(); ?>.jpg" class="card-img-top" alt="<?= $article->getTitre(); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article->getTitre(); ?></h5>
                        <p class="card-text"><?= $article->getTexte(); ?></p>
                        <a href="#" class="btn btn-primary"><?= $article->getDate(); ?></a>
                        <?php if ($utilisateursConnect->isConnect == true) { ?>
                        <a href="article.php?action=update&id=<?= $article->getId(); ?>" class="btn btn-warning">Modifier</a>
                        <?php } ?>
                    </div>
                </div>
                </div>
                <?php
            }
            ?>
        </div>        
    </div>
    <?php
    include_once 'includes/footer.inc.php';
} else {
    header("Location: index.php");
}
?>
