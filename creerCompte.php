<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/fonctions.inc.php";

    $nom_page = "creercompte";

    if(isset($_POST['submit'])) {
        print_r2($_POST);
        print_r2($_FILES);

        //Requête d'insertion de l'utilisateur
        $inserer_utilisateur = "INSERT INTO users(nom, prenom, email, mdp, sid) VALUES (:nom, :prenom, :email, :mdp, :sid)";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($inserer_utilisateur);

        //Sécuriser les paramètres
        $sth->bindValue(":nom", $_POST['nom'], PDO::PARAM_STR);
        $sth->bindValue(":prenom", $_POST['prenom'], PDO::PARAM_STR);
        $sth->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(":mdp", cryptPassword($_POST['mdp']), PDO::PARAM_STR);
        $sth->bindValue(":sid", $_POST['sid'], PDO::PARAM_STR);

        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        if($result == TRUE) {
            $notification = "L'utilisateur a bien été enregistré.";
            $succes_notification = true;
        }
        else {
            $notification = "Erreur d'insertion dans la base de données.";
            $succes_notification = false;
        }

        //Variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        header('Location: index.php');
        exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }
    
    require_once "libs/Smarty.class.php";

    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    $smarty->debugging = true;

    include_once "includes/head.inc.php";
    include_once "includes/menu.inc.php";

    $smarty->display("creerCompte.tpl");
    
    include 'includes/footer.inc.php';
   
    
?>
