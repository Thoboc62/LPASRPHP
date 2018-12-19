<?php
        session_start();

        require_once "config/init.conf.php";
        require_once "config/bdd.conf.php";
        include_once "includes/connexion.inc.php";
        include_once "includes/head.inc.php";
        include_once "includes/fonctions.inc.php";
        require_once "libs/Smarty.class.php";
        
        $nom_page = "index";
        $smarty = new Smarty();

        $smarty->setTemplateDir("templates/");
        $smarty->setCompileDir("templates_c/");
       
        $smarty->debugging = true;
             
        //print_r2($_SESSION);

        include_once "includes/menu.inc.php";

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
        
        $smarty->assign('nom_page', $nom_page);
        $smarty->assign('tab_articles', $tab_articles);
        $smarty->assign('nb_pages', $nb_pages);
        
        $smarty->display("index.tpl");

        include 'includes/footer.inc.php';
