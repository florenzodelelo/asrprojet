<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

include_once 'includes/header.inc.php';
if (isset($_POST['submit'])) {
    $utilisateurs = new utilisateurs();
    $utilisateurs->hydrate($_POST);
    
    //print_r2($utilisateurs);
    
    //recherche de l'utilisateur en bdd
    $utilisateursManager = new utilisateursManager($bdd);
    $utilisateursEnBdd = $utilisateursManager->getByEmail($utilisateurs->getEmail());
    //print_r2($utilisateursEnBdd);
    
    //Vérifier si le couple login/mot de passe correspond
    $isConnect = password_verify($utilisateurs->getMdp(), $utilisateursEnBdd->getMdp()); //$utilisateur car on a fait $utilisateurs->hydrate ($_POST) pour récupérer toutes les infos du formulaire dans $utilisateurs, puis on prend getMdp 
    //var_dump($isConnect);
    
    if($isConnect == true) {
        //Création du SID
        $sid = md5($utilisateurs->getEmail() . time());
        //echo $sid;
        //Création du cookie
        setcookie('sid', $sid, time() + 86400); //Le cookies peut vivre 86400 secondes, soit une journée
        //Affecter le SID à l'objet (l'utilisateur)
        $utilisateurs->setSid($sid);
        //Mise en bdd du sid
        $utilisateursManager->updateByEmail($utilisateurs);
        //var_dump($utilisateursManager->get_result());
    } else {
        
    }
    if ($isConnect == true) {
        $_SESSION['notification']['result'] = 'success';
        $_SESSION['notification']['message'] = 'Vous êtes bien connecté.';
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = 'Oops ! Vérifiez votre login et/ou votre mot de passe.';
        header("Location: connexion.php");
        exit();
    }
        
    
    exit();
}
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Florenzo Delelo</h1>
            <p class="lead">Connexion Utilisateur</p>
            <ul class="list-unstyled">
                <li>Bootstrap 4.5.0</li>
                <li>jQuery 3.5.1</li>
            </ul>
        </div>
</div>
    <?php if(isset($_SESSION['notification'])){?>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-primary" role="alert">
               <?= $_SESSION['notification']['message'] ?>
            </div>
        </div>
    </div>
    <?php
    unset($_SESSION['notification']);
    }
    ?>
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form id="connexionForm" method="POST" action="connexion.php" enctype="multipart/form-data">

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

                <button type="submit" id="submit" name="submit" class="btn btn-primary">Connexion</button>   
            </form>
        </div>
    </div>
<?php
include_once 'includes/footer.inc.php';
?>
