<!DOCTYPE html>
<html lang="en">

    <?php
        session_start();

        require_once "config/init.conf.php";
        require_once "config/bdd.conf.php";
        include_once "includes/connexion.inc.php";
        include_once "includes/head.inc.php";
        include_once "includes/fonctions.inc.php";

        $nom_page = "index";

        //print_r2($_SESSION);

    ?>

    <body>

        <?php include_once "includes/menu.inc.php" ?>

        <!-- Page Content -->
        <div class="container">

            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="mt-5">Blizzard's fan page</h1>
                    <?php include "includes/notification.inc.php"; ?>
                </div>
            </div>

            <?php
                //Affichage des articles par page (pagination)
                $page_courante = empty($_GET['p']) ? 1 : $_GET['p'];

                $index = getIndex($page_courante, _NB_ART_PAR_PAGE);

                $nb_articles = nbTotalArticlesPublie($bdd);
                $nb_pages = ceil($nb_articles / _NB_ART_PAR_PAGE); //ceil() arrondit au nombre entier supérieur


                //Requête de sélection des articles
                $select_article = "SELECT id_articles, titre, texte, DATE_FORMAT(date, '%d/%m/%Y') as date, publie FROM articles WHERE publie=:publie "
                        . "LIMIT :index, :nb_article_par_page";
                /* @var $bdd PDO */

                //Préparation de la requête
                $sth = $bdd->prepare($select_article);

                //Sécuriser les paramètres, qui a comme valeur 1, et doit être un booléen
                $sth->bindValue(":publie", 1, PDO::PARAM_BOOL);
                $sth->bindValue(":nb_article_par_page", _NB_ART_PAR_PAGE, PDO::PARAM_INT);
                $sth->bindValue(":index", $index, PDO::PARAM_INT);

                //Exécution de la requête
                $sth->execute();

                //Association des enregistrements
                $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC); //fetAll récupère toutes les entrées de la BDD d'un coup
                //print_r2($tab_articles);
                //Affichage du titre du 2ème articles
                //echo $tab_articles[0]['titre'];
            ?>

            <div class="row">
                <?php
                    foreach ($tab_articles as $key => $value) {
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
                                    <a class="page-link" href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php
                            }
                        ?>
                    </ul>
                </nav>
            </div>

        </div>

        <?php include 'includes/footer.inc.php' ?>

    </body>

</html>
