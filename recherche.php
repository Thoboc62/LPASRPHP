
<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/head.inc.php";
    include_once "includes/fonctions.inc.php";

    $nom_page = "recherche";

    include "includes/menu.inc.php";

    if(isset($_GET['afficher']) && $_GET['afficher'] == 1) {
        //print_r2($_GET);
        //print_r2($_FILES);
        //exit();

        //Affichage des articles par page (pagination)
        $page_courante = empty($_GET['p']) ? 1 : $_GET['p'];

        $index = getIndex($page_courante, _NB_ART_PAR_PAGE);

        $nb_articles = nbResultatsRecherche($bdd, $_GET['recherche']);
        $nb_pages = ceil($nb_articles / _NB_ART_PAR_PAGE); //ceil() arrondit au nombre entier supérieur

        $recherche = "SELECT * FROM articles WHERE titre LIKE :titre OR texte LIKE :texte LIMIT :index, :nb_article_par_page";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($recherche);

        //Sécuriser les paramètres
        $sth->bindValue(":titre", "%".$_GET['recherche']."%", PDO::PARAM_STR);
        $sth->bindValue(":texte", "%".$_GET['recherche']."%", PDO::PARAM_STR);
        $sth->bindValue(":nb_article_par_page", _NB_ART_PAR_PAGE, PDO::PARAM_INT);
        $sth->bindValue(":index", $index, PDO::PARAM_INT);


        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné

        $tab_result = $sth->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($result);
        // exit();

         $nb_result = nbResultatsRecherche($bdd, "%".$_GET['recherche']."%");

        if($nb_result > 0) {
            $notification = "Correspondances trouvées !";
            $succes_notification = true;
        }
        else {
            $notification = "Aucun résultat trouvé.";
            $succes_notification = false;
        }

        //Variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        // print_r2($_SESSION);
        // exit();
        //header('Location: index.php');
        //exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Recherche dans le site</h1>
            <?php include "includes/notification.inc.php"; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <form action="recherche.php" method="get" enctype="multipart/form-data" id="form_article">
                <input type="hidden" value="1" name="afficher"/>
                <div class="form-group">
                    <label for"titre" class="col-from-label">Votre recherche:</label>
                    <input type="text" class="form-control" id="recherche" name="recherche" placeholder="Votre recherche..." value="" required/>
                </div>
                <button type="submit" class="btn btn-primary" name="" value="">Rechercher</button>
            </form>
        </div>
    </div>

    <?php if(isset($_GET['afficher']) && $_GET['afficher'] == 1) { ?>
        <div class="row">
            <?php
                foreach ($tab_result as $key => $value) {
                    ?>
                        <div class="col-md-6">
                            <div class="card mt-4">
                                <img class="card-img-top" src="img/<?php echo $value['id_articles']; ?>.jpg" alt="<?php echo $value['id_articles']; ?>"/>
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $value['titre']; ?></h4>
                                    <p class="card-text"><?php echo $value['texte']; ?></p>
                                    <a href="#" class="btn btn-primary">Créé le: <?php echo $value['date']; ?></a>
                                    <a href="article.php?action=modifier&id=<?php echo $value['id_articles']; ?>" class="btn btn-warning">Modifier</a>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>

        </div>

        <div class="row">
            <nav aria-label="Page navigation" class="mx-auto mt-4">
                <ul class="pagination">
                    <?php
                        for($i = 1; $i <= $nb_pages; $i++) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?php echo $i; ?>&afficher=1&recherche=<?php echo $_GET['recherche']; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php
                        }
                    ?>
                </ul>
            </nav>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.inc.php' ?>
