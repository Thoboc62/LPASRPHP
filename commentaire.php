<?php

    session_start();
    
    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    
    //si le formulaire a été envoyé
    if(isset($_POST['submit'])){
        
        //requête d'insertion du commentaire dans la base de données
        $inserer_commentaire = "INSERT INTO commentaires(pseudo, email, commentaire, date, id_articles) VALUES (:pseudo, :email, :commentaire, :date, :id_articles)";
        /* @var $bdd PDO */
        
        //préparation de la requête
        $sth = $bdd->prepare($inserer_commentaire);
        
        //Obtention de la date d'aujourd'hui, au format AAAA-MM-JJ
        $cur_date = date("Y-m-d");
        
        //Sécuriser les paramètres
        $sth->bindValue(":pseudo", $_POST['pseudo'], PDO::PARAM_STR);
        $sth->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(":commentaire", $_POST['texte'], PDO::PARAM_STR);
        $sth->bindValue(":date", $cur_date, PDO::PARAM_STR);
        $sth->bindValue(":id_articles", $_POST['id'], PDO::PARAM_INT);
        
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionnée
        
        if($result == TRUE) {
            $notification = "Félicitations votre commentaire est publié !";
            $succes_notification = true;
        }
        else { //si il a été demandé d'ajouter un article, mais que la requête n'a pas fonctionnée, on informe de l'échec d'insertion
            $notification = "Erreur d'insertion dans la base de données.";
            $succes_notification = false;
        }
    }
    else {
        $notification = "Vous n'avez pas soumis le formulaire.";
        $succes_notification = false;
    }
    
    //Enregistrement de la notification dans les variables de session
    $_SESSION['notifications']['message'] = $notification;
    $_SESSION['notifications']['result'] = $succes_notification;

    //redirection vers la page d'accueil
    header('Location: index.php');
    exit();

?>
