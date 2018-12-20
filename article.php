
<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";

    //Variable nécessaire pour la mise en surbrillance du terme dans la barre de menus   
    $nom_page = "gestionarticles";

    //si le visiteur n'est pas connecté, on le redirige vers la page de connexion, et on l'informe
    if(!$is_connect) {
        $_SESSION['notifications']['message'] = "Vous devez vous connecter pour ajouter ou modifier des articles.";
        $_SESSION['notifications']['result'] = false;
        header("Location: connexion.php");
        exit();
    }

    //si le formulaire de connexion a été rempli et soumis
    if(isset($_POST['submit'])) {

        //Vérifier si la case a été cochée ou non, pour savoir si il faut le publier ensuite
        $publie = isset($_POST['publie']) ? 1 : 0;

        //Obtention de la date d'aujourd'hui, au format AAAA-MM-JJ
        $cur_date = date("Y-m-d");

        //si l'utilisateur demande un ajout d'article
        if($_POST['submit']=="ajouter") {
            
            //requête d'insertion de l'article dans la base de données
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
        
        //si l'utilisateur demande une mise à jour d'un article existant
        elseif($_POST['submit']=="modifier") {
            
            //requete de mise à jour de l'article
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
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionnée

        if($_POST['submit'] == "ajouter") { //si il a été demandé d'ajouter un article, et que la requête a donné un résultat positif (insertion faite), on informe que l'article a été ajouté
            if($result == TRUE) {
                $notification = "Félicitations votre article est publié !";
                $succes_notification = true;
            }
            else { //si il a été demandé d'ajouter un article, mais que la requête n'a pas fonctionnée, on informe de l'échec d'insertion
                $notification = "Erreur d'insertion dans la base de données.";
                $succes_notification = false;
            }
        }
        elseif($_POST['submit'] == "modifier") { //si il a été demandé de mettre à jour un article, et que la requête a donné un résultat positif, on informe de la mise à jour de l'article
            if($result == TRUE) {
                $notification = "Votre article a bien été mis à jour avec les informations saisies.";
                $succes_notification = true;

            }
            else { //sinon, la mise à jour n'a pas été effectuée
                $notification = "Erreur de mise à jour des informations dans la base de données.";
                $succes_notification = false;
            }
        }
        
        //Traitement dans le cas d'un ajout d'article
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

        //Enregistrement de la notification dans les variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        header('Location: index.php');
        exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }

    $action = $_GET['action']; //récupérer l'action demandée par l'utilisateur (ajout/modification) depuis l'URL, servira pour affichage de l'action à effectuer dans le bouton du formulaire

    //afficher les données de l'article selectionné sur la page d'accueil pour modification, ou suppression
    if($action == "modifier") {
        
        //requete de selection de l'article
        $select_article_a_modifier = "SELECT * FROM articles WHERE id_articles = :id";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($select_article_a_modifier);

        //Sécuriser les paramètres
        $sth->bindValue(":id", $_GET['id'], PDO::PARAM_INT);

        //Exécution de la requête
        $result_select_article_a_modifier = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        $tab_article = $sth->fetch(PDO::FETCH_ASSOC);

    }
    elseif($action=="supprimer"){
        
        //requete suppression de l'article
        $requete_suppr_article = "DELETE FROM articles WHERE id_articles = :id";
        /* @var $bdd PDO */
        
        //Préparation de la requête
        $sth = $bdd->prepare($requete_suppr_article);

        //Sécuriser les paramètres
        $sth->bindValue(":id", $_GET['id'], PDO::PARAM_INT);

        //Exécution de la requête
        $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        
        //création d'une notification pour informer de la suppression de l'article
        $notification = "L'article a bien été supprimé.";
        $succes_notification = true;
        
        //enregistrement de la notification dans les variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;
        
        //redirection vers la page d'accueil
        header('Location: index.php');
        exit();
        
    }
    else { //on créé un tableau vide si on ne doit pas mettre à jour un article, pour éviter notamment des erreurs
        $tab_article = array(
            'id_articles' => '',
            'texte' => '',
            'titre' => '',
            'publie' => ''
        );
    }
    
    include_once "includes/fonctions.inc.php";

    //Mise en place de Smarty
    require_once "libs/Smarty.class.php";

    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    //envoi des variables pour affichage sur le template
    $smarty->assign('actionTexte', ucfirst($_GET['action'])); //action telle qu'elle sera affichée en titre et dans le bouton du formulaire
    $smarty->assign('action', $_GET['action']); //action telle qu'elle sera utilisée comme variable pour traitement du formulaire
    $smarty->assign('id_articles', $tab_article['id_articles']);
    $smarty->assign('titre', $tab_article['titre']);
    $smarty->assign('texte', $tab_article['texte']);
    $smarty->assign('publie', $tab_article['publie']);

    $smarty->debugging = true;

    include_once "includes/head.inc.php";
    include_once "includes/menu.inc.php";
    include_once "includes/notification.inc.php";    

    //affichage de la page
    $smarty->display("article.tpl");

    include_once "includes/footer.inc.php";
    
?>
