
<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";

    $nom_page = "gestionarticles";

    if(!$is_connect) {
        $_SESSION['notifications']['message'] = "Vous devez vous connecter pour ajouter ou modifier des articles.";
        $_SESSION['notifications']['result'] = false;
        header("Location: connexion.php");
        exit();
    }

    if(isset($_POST['submit'])) {
        print_r2($_POST);
        print_r2($_FILES);
        //exit();

        //Vérifier si la case a été cochée ou non
        $publie = isset($_POST['publie']) ? 1 : 0;

        //Requête d'insertion des articles
        $cur_date = date("Y-m-d");

        if($_POST['submit']=="ajouter") {
            $inserer_article = "INSERT INTO articles(titre, texte, date, publie) VALUES (:titre, :texte, :date, :publie)";
            /* @var $bdd PDO */

            //Préparation de la requête
            $sth = $bdd->prepare($inserer_article);

            //Sécuriser les paramètres
            $sth->bindValue(":titre", $_POST['titre'], PDO::PARAM_STR);
            $sth->bindValue(":texte", $_POST['texte'], PDO::PARAM_STR);
            $sth->bindValue(":date", $cur_date, PDO::PARAM_STR);
            $sth->bindValue(":publie", $publie, PDO::PARAM_INT);
        }
        elseif($_POST['submit']=="modifier") {
            $modifier_article = "UPDATE articles SET titre=:titre, texte=:texte, publie=:publie WHERE id_articles=:id";
            /* @var $bdd PDO */

            //Préparation de la requête
            $sth = $bdd->prepare($modifier_article);

            //Sécuriser les paramètres
            $sth->bindValue(":titre", $_POST['titre'], PDO::PARAM_STR);
            $sth->bindValue(":texte", $_POST['texte'], PDO::PARAM_STR);
            $sth->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
            $sth->bindValue(":publie", $publie, PDO::PARAM_INT);
        }


        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné

        // var_dump($result);
        // exit();

        if($_POST['submit'] == "ajouter") {
            if($result == TRUE) {
                $notification = "Félicitations votre article est publié !";
                $succes_notification = true;
            }
            else {
                $notification = "Erreur d'insertion dans la base de données.";
                $succes_notification = false;
            }
        }
        elseif($_POST['submit'] == "modifier") {
            if($result == TRUE) {
                $notification = "Votre article a bien été mis à jour avec les informations saisies.";
                $succes_notification = true;

            }
            else {
                $notification = "Erreur de mise à jour des informations dans la base de données.";
                $succes_notification = false;
            }
        }

        if($_POST['submit']=="ajouter") {
            //Récupération du dernier id pour trouver l'id de l'article à insérer dans la BDD
            $id_article = $bdd->lastInsertId();

            //Vérification de l'image
            if($_FILES['image']['error']==0) {
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $result_img = move_uploaded_file($_FILES['image']['tmp_name'], "img/" . $id_article . "." . $extension); //on stocke dans $result_img le resultat de la commande

                $notification .= $result_img == TRUE ? "" : "Erreur lors du déplacement de l'image."; //on ajoute à la notification précédente la nouvelle (concaténation)
            }
        }

        //Variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        // print_r2($_SESSION);
        // exit();
        header('Location: index.php');
        exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }

    $action = $_GET['action'];

    //afficher les données d'un article dans le formulaire pour modification
    if($action == "modifier") {
        $select_article_a_modifier = "SELECT * FROM articles WHERE id_articles = :id";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($select_article_a_modifier);

        //Sécuriser les paramètres
        $sth->bindValue(":id", $_GET['id'], PDO::PARAM_INT);

        //Exécution de la requête
        $result_select_article_a_modifier = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        $tab_article = $sth->fetch(PDO::FETCH_ASSOC);
        //print_r2($article_a_modifier);

    }
    else {
        $tab_article = array(
            'id_articles' => '',
            'texte' => '',
            'titre' => '',
            'publie' => ''
        );
    }
    
    include_once "includes/fonctions.inc.php";

    require_once "libs/Smarty.class.php";

    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    $smarty->assign('actionTexte', ucfirst($_GET['action']));
    $smarty->assign('action', $_GET['action']);
    $smarty->assign('id_articles', $tab_article['id_articles']);
    $smarty->assign('titre', $tab_article['titre']);
    $smarty->assign('texte', $tab_article['texte']);
    $smarty->assign('publie', $tab_article['publie']);

    $smarty->debugging = true;

    include_once "includes/head.inc.php";
    include_once "includes/menu.inc.php";
    include_once "includes/notification.inc.php";    

    $smarty->display("article.tpl");

    include_once "includes/footer.inc.php";
    
?>
