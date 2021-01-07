<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

include_once 'includes/header.inc.php';

if (isset($_POST['submit'])) {
    $utilisateurs = new utilisateurs();
    $utilisateurs->hydrate($_POST);

    //Ajout de l'utilisateur
    $utilisateursManager = new utilisateursManager($bdd);
    $utilisateursManager->add($utilisateurs);

    if ($utilisateursManager->get_result() == true) {
        $_SESSION['notification']['result'] = 'success';
        $_SESSION['notification']['message'] = 'Vous êtes bien enregistré';
    } else {
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = 'Oops ! Une erreur est survenue. Veuillez réessayer ultérieurement.';
    }

    header("Location: index.php");
    exit();
}
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Florenzo Delelo</h1>
            <p class="lead">Ajouter un utilisateur</p>
            <ul class="list-unstyled">
                <li>Bootstrap 4.5.0</li>
                <li>jQuery 3.5.1</li>
            </ul>
        </div>
</div>

    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form id="utilisateursForm" method="POST" action="utilisateurs.php" enctype="multipart/form-data">

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">Nom</label>
                        <input type="text" id="tite" name="nom" class="form-control" value="" placeholder="">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">Prénom</label>
                        <input type="text" id="tite" name="prenom" class="form-control" value="" placeholder="">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">E-M@IL</label>
                        <input type="email" id="tite" name="email" class="form-control" value="" placeholder="">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">Mot de passe</label>
                        <input type="password" size="255" id="tite" name="mdp" class="form-control" value="" placeholder="">
                    </div>
                </div>
               <!-- <div class="col-lg-12">
                    <div class="form-group">
                        <label for="titre">Sid</label>
                        <input type="text" id="tite" name="sid" class="form-control" value="" placeholder="">
                    </div>
                </div> -->  
                <button type="submit" id="submit" name="submit" class="btn btn-primary">M'enregistrer</button>   
            </form>
        </div>
    </div>

<?php
include_once 'includes/footer.inc.php';
?>