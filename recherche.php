
<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/head.inc.php";
    include_once "includes/fonctions.inc.php";

    //Variable nécessaire pour la mise en surbrillance du terme dans la barre de menus
    $nom_page = "recherche";
    
    //Mise en place de Smarty
    
    require_once "libs/Smarty.class.php";
    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    $smarty->debugging = true;

    //insertion barre supérieure (menus)
    include "includes/menu.inc.php";
    
    //si la recherche d'un motif est demandée
    if(isset($_GET['afficher']) && $_GET['afficher'] == 1 && isset($_GET['recherche'])) {

        //Affichage des articles par page (pagination)
        $page_courante = empty($_GET['p']) ? 1 : $_GET['p'];

        $index = getIndex($page_courante, _NB_ART_PAR_PAGE);

        $nb_articles = nbResultatsRecherche($bdd, $_GET['recherche']); //nombre d'articles correspondant à la recherche
        $nb_pages = ceil($nb_articles / _NB_ART_PAR_PAGE); //ceil() arrondit au nombre entier supérieur, détermine le nombre de pasges nécessaires

        //requete de selection des articles avec le motif donné par le visiteur
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

        $nb_result = nbResultatsRecherche($bdd, "%".$_GET['recherche']."%"); //nombre de resultats pour la recherche
        
        $afficher_resultats = 1;
        
        //envoi des variables à Smarty
        $smarty->assign('tab_result', $tab_result);
        $smarty->assign('nb_pages', $nb_pages);
        $smarty->assign('recherche', $_GET['recherche']);

    }
    else { //si on ne demande pas l'affichage (formulaire non rempli une premiere fois), on demandera a Smarty de ne pas réaliser le traitement de l'affichage des résultats
        $afficher_resultats = 0;
    }
    
    //envoi des variables à Smarty
    $smarty->assign('nom_page', $nom_page);
    $smarty->assign('afficher_resultats', $afficher_resultats);

    //affichage de la page
    $smarty->display("recherche.tpl");

    include 'includes/footer.inc.php';
    
?>
    
     