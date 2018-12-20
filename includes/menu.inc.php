<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
  <div class="container">
    <a class="navbar-brand" href="#">Blizzard's fan page</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php if($nom_page == "index") { echo 'active';}?>">
          <a class="nav-link" href="index.php">Accueil
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item <?php if($nom_page == "recherche") { echo 'active';}?>">
          <a class="nav-link" href="recherche.php">Rechercher</a>
        </li>
        <li class="nav-item <?php if($nom_page == "creercompte") { echo 'active';}?>">
          <a class="nav-link" href="creerCompte.php">Créer un compte</a>
        </li>

        <?php
            if($is_connect) { ?>
                <li class="nav-item <?php if($nom_page == "gestionarticles") { echo 'active';}?>">
                  <a class="nav-link" href="article.php?action=ajouter">Ajouter un article</a>
                </li>
        <?php } ?>

        <?php
            if($is_connect) { ?>
                <li class="nav-item">
                  <a class="nav-link" href="deconnexion.php">Se déconnecter</a>
                </li>
        <?php }
            else { ?>
                <li class="nav-item <?php if($nom_page == "connexion") { echo 'active';}?>">
                  <a class="nav-link" href="connexion.php">Se connecter</a>
                </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
