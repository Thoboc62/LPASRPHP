<?php
    //afficher une notification
    if(isset($_SESSION['notifications'])) { //si une notification est enregistrée dans les variables de session
        if($_SESSION['notifications']['result']==true) { //on regarde quelle couleur donner à la notification
            $color_notification = "success";
        }
        else{
            $color_notification = "danger";
        }
        
        //transmettre à Smarty les données des notifications (couleur, conteu notification)
        $smarty->assign('color_notification', $color_notification);
        $smarty->display("notifications.tpl");
        
        //on supprime la notification (ne sera plus affichée après rafraichissement de la page)
        unset($_SESSION['notifications']);
}

?>