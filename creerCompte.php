<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/head.inc.php";
    include_once "includes/fonctions.inc.php";
    
    //Variable nécessaire pour la mise en surbrillance du terme dans la barre de menus
    $nom_page = "creercompte";
    
    //Mise en place de Smarty
    
    require_once "libs/Smarty.class.php";
    $smarty = new Smarty();
    
    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");
    $smarty->debugging = true;
    
    //Insertion barre menu
    include_once "includes/menu.inc.php";
    
    //Affichage de la notification, si besoin
    include_once 'includes/notification.inc.php';

    if(isset($_POST['submit'])) { //si le formulaire a été soumis

        //Requête d'insertion de l'utilisateur
        $inserer_utilisateur = "INSERT INTO users(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp)";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($inserer_utilisateur);

        //Sécuriser les paramètres
        $sth->bindValue(":nom", $_POST['nom'], PDO::PARAM_STR);
        $sth->bindValue(":prenom", $_POST['prenom'], PDO::PARAM_STR);
        $sth->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(":mdp", cryptPassword($_POST['mdp']), PDO::PARAM_STR);

        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        
        if($result == TRUE) { //si l'utilisateur a bien été inséré dans la base de données, on informe l'utilisateur du succes de l'opération
            $notification = "L'utilisateur a bien été enregistré.";
            $succes_notification = true;
        }
        else { //sinon, on l'informe d'un échec d'insertion
            $notification = "Erreur d'insertion dans la base de données.";
            $succes_notification = false;
        }

        //Enregistrement dans les variables de session la notification
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        header('Location: index.php');
        exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }

    //afficher le formulaire d'inscription si il n'a pas été soumis précédemment
    $smarty->display("creerCompte.tpl");
    
    include 'includes/footer.inc.php';
    
?>
