<?php

    session_start();
    setcookie("sid", "deconnexion", -1); //on supprime le cookie qui maintient la connexion
    //session_destroy();
    
    $notification = "Vous avez été déconnecté.";
    $succes_notification = true;
    
    //enregistrement de la notification dans les variables de session
    $_SESSION['notifications']['message'] = $notification;
    $_SESSION['notifications']['result'] = $succes_notification;
    
    header('Location: index.php');
    
    exit();
    

?>

