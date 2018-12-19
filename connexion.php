<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/head.inc.php";
    include_once "includes/fonctions.inc.php";

    //Variable nécessaire pour la mise en surbrillance du terme dans la barre de menus
    $nom_page = "connexion";
    
    //Mise en place de Smarty
        
    require_once "libs/Smarty.class.php";
    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    $smarty->debugging = true;

    //Insertion barre de menus
    include "includes/menu.inc.php";
    
    //Affichage de la notification, si besoin
    include_once 'includes/notification.inc.php';

    if($is_connect == TRUE) { //si l'utilisateur est déjà connecté, on le redirige, car il n'a pas besoin d'accéder à la page de connexion
        //on créé une notification pour avertir le visiteur
        $_SESSION['notifications']['message'] = "Vous êtes déjà connecté.";
        $_SESSION['notifications']['result'] = true;
        //on le redirige sur la page d'accueil
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['submit'])) { //si un utilisateur demande la connexion avec le formulaire

        //On vérifie si les identifiants correspondent à un utilisateur enregistré
        $select_utilisateur_count = "SELECT count(*) as total FROM users WHERE (email=:email AND mdp=:mdp)";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($select_utilisateur_count);

        //Sécuriser les paramètres
        $sth->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(":mdp", cryptPassword($_POST['mdp']), PDO::PARAM_STR);

        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné

        //on compte le nombre de comptes avec les identifiants (identifiant + MDP corrects) renseignés
        $nb_result = $sth->fetch(PDO::FETCH_ASSOC);

        if($nb_result['total'] > 0) { //si un utilisateur est trouvé avec les identifiants renseignés, on le connecte
            //on créé un SID (identifiant de session unique généré à partir de l'email et de la date depuis le 1er janvier 1970)
            $sid = sid($_POST['email']);
            
            //on met à jour l'utilisateur en ajoutant le SID
            $sql_update = "UPDATE users SET sid=:sid WHERE email=:email";
            $sth_update = $bdd->prepare($sql_update);
            $sth_update->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
            $sth_update->bindValue(":sid", $sid, PDO::PARAM_STR);

            $result_update = $sth_update->execute();

            //on créé un cookie dans le navigateur pour maintenir la connexion, même après fermeture du navigateur (pendant un temps donné)
            //setcookie(nomCookie, valeurCookie, tempsConservation);
            setcookie("sid", $sid, time()+600); //durée de la session: 10minutes

            //création d'une notification informant que la connexion a été effectuée
            $notification = "Vous êtes maintenant connecté.";
            $succes_notification = true;
        }
        else {
            //création d'une notification informant que la connexion a échouée
            $notification = "Erreur d'authentification.";
            $succes_notification = false;
        }

        //enregistrement de la notification dans les variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //si la connexion est effectuée, on redirige vers l'accueil, sinon on redirige vers la page de connexion
        $succes_notification == TRUE ? header("Location: index.php") : header("Location: connexion.php");

        exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }
    
    //Affichage du formulaire de connexion en cas d'echec de connexion ou si le formulaire n'a pas été rempli
    $smarty->display("connexion.tpl");

    include 'includes/footer.inc.php';
    
 ?>
