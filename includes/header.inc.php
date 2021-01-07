<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>FLORENZO DELELO</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">Accueil du blog</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <?php if($utilisateursConnect->isConnect == true){ ?>
          <li class="nav-item">
              <a class="nav-link" href="article.php">Article</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="utilisateurs.php">Utilisateurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <?php } ?>
          <?php if($utilisateursConnect->isConnect == false){ ?>
          <li class="nav-item">
              <a class="nav-link" href="connexion.php">Connexion</a>
          </li>
          <?php } ?>
          <?php if($utilisateursConnect->isConnect == true){ ?> 
          <li class="nav-item">
              <a class="nav-link" href="deconnexion.php">Déconnexion</a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>